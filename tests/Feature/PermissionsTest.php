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

it('usuário comum não consegue lançar resultado', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    $game = Game::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.games.result.store', $game), ['home_score' => 1, 'away_score' => 0])
        ->assertForbidden();

    expect($game->fresh()->status)->not->toBe('finished');
});

it('admin lança resultado e a pontuação é recalculada', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $game = Game::factory()->create(['match_datetime' => now()->subDay()]);

    $bettor = User::factory()->create();
    $bettor->assignRole('user');
    Bet::factory()->create([
        'user_id' => $bettor->id, 'game_id' => $game->id, 'home_score' => 2, 'away_score' => 1,
    ]);

    $this->actingAs($admin)
        ->put(route('admin.games.result.store', $game), ['home_score' => 2, 'away_score' => 1])
        ->assertRedirect();

    expect($game->fresh()->status)->toBe('finished')
        ->and(Bet::first()->points_earned)->toBe((int) config('bolao.scoring.exact'));
});

it('usuário comum não acessa o CRUD de jogos', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->actingAs($user)->get(route('admin.games.index'))->assertForbidden();
});
