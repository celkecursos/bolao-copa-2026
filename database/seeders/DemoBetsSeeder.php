<?php

namespace Database\Seeders;

use App\Models\Bet;
use App\Models\Game;
use App\Models\User;
use App\Services\ScoringService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoBetsSeeder extends Seeder
{
    /**
     * Cria usuários de demonstração, finaliza os jogos já ocorridos com
     * placares aleatórios e gera palpites — assim o ranking tem dados.
     */
    public function run(): void
    {
        $users = collect(range(1, 12))->map(function (int $i) {
            $user = User::firstOrCreate(
                ['email' => "jogador{$i}@bolao.test"],
                [
                    'name' => "Jogador {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles('user');

            return $user;
        });

        $games = Game::all();

        // Palpites de todos os usuários demo em todos os jogos.
        foreach ($users as $user) {
            foreach ($games as $game) {
                Bet::firstOrCreate(
                    ['user_id' => $user->id, 'game_id' => $game->id],
                    ['home_score' => rand(0, 3), 'away_score' => rand(0, 3)]
                );
            }
        }

        // Finaliza com placar aleatório apenas jogos passados AINDA não finalizados
        // (no fluxo com a API os resultados reais já vêm preenchidos e são preservados).
        $scoring = app(ScoringService::class);

        foreach ($games as $game) {
            if ($game->match_datetime->isPast() && $game->status !== 'finished') {
                $game->home_score = rand(0, 4);
                $game->away_score = rand(0, 4);
                $game->status = 'finished';
                $game->saveQuietly();
            }

            if ($game->status === 'finished') {
                $scoring->recalculateGame($game);
            }
        }
    }
}
