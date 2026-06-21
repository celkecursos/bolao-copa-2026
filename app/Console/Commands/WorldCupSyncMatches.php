<?php

namespace App\Console\Commands;

use App\Jobs\RecalculateGamePointsJob;
use App\Models\Game;
use App\Models\Team;
use App\Services\FootballDataService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class WorldCupSyncMatches extends Command
{
    protected $signature = 'worldcup:sync-matches {--dateFrom=} {--dateTo=}';

    protected $description = 'Importa/atualiza times, jogos e resultados reais da Copa via football-data.org.';

    private const STAGE_MAP = [
        'GROUP_STAGE' => 'group',
        'LAST_32' => 'round_32',
        'PLAYOFFS' => 'round_32',
        'LAST_16' => 'round_16',
        'QUARTER_FINALS' => 'quarter',
        'SEMI_FINALS' => 'semi',
        'THIRD_PLACE' => 'third_place',
        'FINAL' => 'final',
    ];

    public function handle(FootballDataService $api): int
    {
        $matches = $api->competitionMatches($this->option('dateFrom'), $this->option('dateTo'));

        if (empty($matches)) {
            $this->warn('Nenhuma partida retornada pela API (verifique token/plano/conexão).');

            return self::SUCCESS;
        }

        // Evita poluir a auditoria com a importação automática.
        Game::disableAuditing();

        $imported = 0;
        $finished = 0;

        foreach ($matches as $m) {
            $home = $m['homeTeam'] ?? [];
            $away = $m['awayTeam'] ?? [];

            // Pula confrontos ainda indefinidos (mata-mata sem times sorteados).
            if (empty($home['id']) || empty($away['id'])) {
                continue;
            }

            $group = ! empty($m['group']) ? str_replace('GROUP_', '', $m['group']) : null;

            $homeTeam = $this->upsertTeam($home, $group);
            $awayTeam = $this->upsertTeam($away, $group);

            $homeScore = data_get($m, 'score.fullTime.home');
            $awayScore = data_get($m, 'score.fullTime.away');
            $status = match ($m['status'] ?? '') {
                'FINISHED' => 'finished',
                'IN_PLAY', 'PAUSED' => 'live',
                default => 'scheduled',
            };

            $game = Game::updateOrCreate(
                ['external_id' => $m['id']],
                [
                    'home_team_id' => $homeTeam->id,
                    'away_team_id' => $awayTeam->id,
                    'group' => $group,
                    'stage' => self::STAGE_MAP[$m['stage'] ?? ''] ?? 'round_32',
                    'match_datetime' => Carbon::parse($m['utcDate'])->utc(),
                    'home_score' => $homeScore,
                    'away_score' => $awayScore,
                    'status' => $status,
                ]
            );

            $imported++;

            if ($status === 'finished' && $homeScore !== null) {
                RecalculateGamePointsJob::dispatch($game->id);
                $finished++;
            }
        }

        Game::enableAuditing();

        $this->info("Importados/atualizados: {$imported} jogos ({$finished} finalizados).");

        return self::SUCCESS;
    }

    private function upsertTeam(array $data, ?string $group): Team
    {
        $team = Team::firstOrNew(['external_id' => $data['id']]);
        $team->name = $data['name'] ?? $data['shortName'] ?? ('Time '.$data['id']);
        $team->code = $data['tla'] ?? $team->code;
        $team->flag = $data['crest'] ?? $team->flag;
        // Só define o grupo quando informado (jogos de mata-mata vêm sem grupo).
        if ($group) {
            $team->group = $group;
        }
        $team->save();

        return $team;
    }
}
