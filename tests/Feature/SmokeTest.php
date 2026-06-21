<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\WorldCup2026Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(WorldCup2026Seeder::class);
});

it('renderiza as telas do super-admin', function () {
    $admin = User::role('super-admin')->first();
    $game = App\Models\Game::first();

    $this->actingAs($admin);

    $this->get(route('dashboard'))->assertOk();
    $this->get(route('bets.index'))->assertOk();
    $this->get(route('admin.teams.index'))->assertOk();
    $this->get(route('admin.teams.create'))->assertOk();
    $this->get(route('admin.games.index'))->assertOk();
    $this->get(route('admin.games.create'))->assertOk();
    $this->get(route('admin.games.result', $game))->assertOk();
    $this->get(route('admin.users.index'))->assertOk();
});

it('usuário comum acessa dashboard e palpites', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->actingAs($user);

    $this->get(route('dashboard'))->assertOk();
    $this->get(route('bets.index'))->assertOk();
});
