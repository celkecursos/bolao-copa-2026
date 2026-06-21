<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FootballDataService
{
    /**
     * Busca partidas da competição configurada via /competitions/{id}/matches,
     * respeitando o rate limit informado nos headers da resposta.
     *
     * @return array<int, array<string, mixed>>
     */
    public function competitionMatches(?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): array
    {
        $cfg = config('services.football_data');

        if (empty($cfg['token'])) {
            Log::warning('football-data: FOOTBALL_DATA_TOKEN não configurado.');

            return [];
        }

        try {
            $response = Http::withHeaders(['X-Auth-Token' => $cfg['token']])
                ->timeout(20)
                ->get("{$cfg['base_url']}/competitions/{$cfg['competition']}/matches", array_filter([
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'status' => $status,
                ]));

            // Respeita o rate limit lendo os headers (free tier ~10 req/min).
            // (header vazio = informação ausente -> não aguarda)
            $available = $response->header('X-Requests-Available-Minute');
            if (! blank($available) && (int) $available <= 0) {
                $reset = (int) ($response->header('X-RequestCounter-Reset') ?: 60);
                Log::warning("football-data: rate limit atingido, aguardando {$reset}s.");
                sleep(min($reset, 60));
            }

            if ($response->failed()) {
                Log::error('football-data: requisição falhou.', ['status' => $response->status()]);

                return [];
            }

            return $response->json('matches', []);
        } catch (\Throwable $e) {
            Log::error('football-data: exceção - '.$e->getMessage());

            return [];
        }
    }

    /** Atalho para partidas finalizadas. */
    public function finishedMatches(?string $dateFrom = null, ?string $dateTo = null): array
    {
        return $this->competitionMatches($dateFrom, $dateTo, 'FINISHED');
    }
}
