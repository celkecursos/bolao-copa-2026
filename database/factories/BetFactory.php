<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Bet>
 */
class BetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'game_id' => Game::factory(),
            'home_score' => fake()->numberBetween(0, 3),
            'away_score' => fake()->numberBetween(0, 3),
        ];
    }
}
