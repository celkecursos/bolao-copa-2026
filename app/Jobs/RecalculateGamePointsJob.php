<?php

namespace App\Jobs;

use App\Models\Game;
use App\Services\ScoringService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RecalculateGamePointsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $gameId)
    {
    }

    public function handle(ScoringService $scoring): void
    {
        $game = Game::with('bets')->find($this->gameId);

        if ($game) {
            $scoring->recalculateGame($game);
        }
    }
}
