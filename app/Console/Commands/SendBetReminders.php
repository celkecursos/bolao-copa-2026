<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\User;
use App\Notifications\BetReminderNotification;
use Illuminate\Console\Command;

class SendBetReminders extends Command
{
    protected $signature = 'matches:send-reminders {--hours=6}';

    protected $description = 'Lembra usuários que ainda não palpitaram em jogos prestes a começar.';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');

        $games = Game::with('bets')
            ->where('status', 'scheduled')
            ->whereBetween('match_datetime', [now(), now()->addHours($hours)])
            ->get();

        $sent = 0;

        foreach ($games as $game) {
            $alreadyBet = $game->bets->pluck('user_id')->all();

            $pending = User::role('user')
                ->whereNotIn('id', $alreadyBet)
                ->get();

            foreach ($pending as $user) {
                $user->notify(new BetReminderNotification($game));
                $sent++;
            }
        }

        $this->info("Lembretes enviados: {$sent}.");

        return self::SUCCESS;
    }
}
