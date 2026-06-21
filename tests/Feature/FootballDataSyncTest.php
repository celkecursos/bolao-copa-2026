<?php

use App\Models\Game;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('importa os jogos da API e ignora confrontos indefinidos', function () {
    config(['services.football_data.token' => 'fake-token']);

    Http::fake([
        '*/competitions/*/matches*' => Http::response([
            'matches' => [
                [
                    'id' => 537327,
                    'utcDate' => '2026-06-11T19:00:00Z',
                    'status' => 'FINISHED',
                    'stage' => 'GROUP_STAGE',
                    'group' => 'GROUP_A',
                    'homeTeam' => ['id' => 1, 'name' => 'Mexico', 'tla' => 'MEX', 'crest' => 'https://x/mex.png'],
                    'awayTeam' => ['id' => 2, 'name' => 'South Africa', 'tla' => 'RSA', 'crest' => 'https://x/rsa.png'],
                    'score' => ['fullTime' => ['home' => 2, 'away' => 0]],
                ],
                [
                    // Mata-mata ainda sem times sorteados -> deve ser ignorado.
                    'id' => 999,
                    'utcDate' => '2026-07-10T19:00:00Z',
                    'status' => 'SCHEDULED',
                    'stage' => 'LAST_16',
                    'group' => null,
                    'homeTeam' => ['id' => null, 'name' => null],
                    'awayTeam' => ['id' => null, 'name' => null],
                    'score' => ['fullTime' => ['home' => null, 'away' => null]],
                ],
            ],
        ], 200),
    ]);

    $this->artisan('worldcup:sync-matches')->assertExitCode(0);

    expect(Team::count())->toBe(2)
        ->and(Game::count())->toBe(1);

    $game = Game::first();
    expect($game->stage)->toBe('group')
        ->and($game->group)->toBe('A')
        ->and($game->status)->toBe('finished')
        ->and($game->home_score)->toBe(2)
        ->and($game->external_id)->toBe(537327)
        ->and($game->homeTeam->code)->toBe('MEX');
});
