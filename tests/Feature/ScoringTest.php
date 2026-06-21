<?php

use App\Models\Bet;
use App\Models\Game;
use App\Models\User;
use App\Services\RankingService;
use App\Services\ScoringService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('pontua o placar exato com a pontuação máxima', function () {
    $s = new ScoringService();
    expect($s->points(2, 1, 2, 1))->toBe((int) config('bolao.scoring.exact'));
});

it('pontua o acerto do vencedor', function () {
    $s = new ScoringService();
    expect($s->points(3, 0, 1, 0))->toBe((int) config('bolao.scoring.winner'));
});

it('pontua o acerto parcial (um placar) sem acertar o vencedor', function () {
    $s = new ScoringService();
    // palpite empate 2x2, resultado 2x1 -> acerta os gols do mandante, mas erra o vencedor
    expect($s->points(2, 2, 2, 1))->toBe((int) config('bolao.scoring.partial'));
});

it('zera quando erra tudo', function () {
    $s = new ScoringService();
    expect($s->points(0, 3, 3, 0))->toBe(0);
});

it('recálculo é idempotente', function () {
    $game = Game::factory()->create([
        'home_score' => 2, 'away_score' => 1, 'status' => 'finished',
    ]);
    $user = User::factory()->create();
    Bet::factory()->create([
        'user_id' => $user->id, 'game_id' => $game->id, 'home_score' => 2, 'away_score' => 1,
    ]);

    $s = new ScoringService();
    $s->recalculateGame($game->fresh());
    $s->recalculateGame($game->fresh());

    expect(Bet::first()->points_earned)->toBe((int) config('bolao.scoring.exact'));
});

it('o ranking desempata por número de placares exatos', function () {
    $g1 = Game::factory()->create(['home_score' => 2, 'away_score' => 1, 'status' => 'finished']);
    $g2 = Game::factory()->create(['home_score' => 1, 'away_score' => 0, 'status' => 'finished']);

    $exato = User::factory()->create(['name' => 'Exato']);
    $vencedor = User::factory()->create(['name' => 'Vencedor']);

    // "Exato": 1 placar exato (10 pts) no g1.
    Bet::factory()->create(['user_id' => $exato->id, 'game_id' => $g1->id, 'home_score' => 2, 'away_score' => 1]);
    // "Vencedor": 2 acertos de vencedor (5+5 = 10 pts).
    Bet::factory()->create(['user_id' => $vencedor->id, 'game_id' => $g1->id, 'home_score' => 3, 'away_score' => 2]);
    Bet::factory()->create(['user_id' => $vencedor->id, 'game_id' => $g2->id, 'home_score' => 2, 'away_score' => 0]);

    $s = new ScoringService();
    $s->recalculateGame($g1->fresh());
    $s->recalculateGame($g2->fresh());

    $ranking = app(RankingService::class)->ranking();

    // Empate em 10 pontos -> quem tem mais placares exatos fica em 1º.
    expect($ranking->first()->id)->toBe($exato->id)
        ->and($ranking->first()->total_points)->toBe(10)
        ->and($ranking->get(1)->id)->toBe($vencedor->id);
});
