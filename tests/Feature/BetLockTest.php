<?php

use App\Models\Bet;
use App\Models\Game;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

function commonUser(): User
{
    $user = User::factory()->create();
    $user->assignRole('user');

    return $user;
}

it('não permite palpitar depois do prazo', function () {
    $user = commonUser();
    $game = Game::factory()->create(['match_datetime' => now()->subHour()]);

    $this->actingAs($user)
        ->post(route('bets.store', $game), ['home_score' => 1, 'away_score' => 0])
        ->assertSessionHasErrors('home_score');

    expect(Bet::count())->toBe(0);
});

it('permite palpitar antes do prazo', function () {
    $user = commonUser();
    $game = Game::factory()->create(['match_datetime' => now()->addDay()]);

    $this->actingAs($user)
        ->post(route('bets.store', $game), ['home_score' => 2, 'away_score' => 1])
        ->assertSessionHasNoErrors();

    expect(Bet::where('user_id', $user->id)->where('game_id', $game->id)->exists())->toBeTrue();
});

it('bloqueia empate no palpite de mata-mata', function () {
    $user = commonUser();
    $game = Game::factory()->create([
        'match_datetime' => now()->addDay(), 'stage' => 'round_16', 'group' => null,
    ]);

    $this->actingAs($user)
        ->post(route('bets.store', $game), ['home_score' => 1, 'away_score' => 1])
        ->assertSessionHasErrors('home_score');

    expect(Bet::count())->toBe(0);
});
