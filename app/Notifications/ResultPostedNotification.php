<?php

namespace App\Notifications;

use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResultPostedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Game $game)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->game->loadMissing(['homeTeam', 'awayTeam']);

        $home = $this->game->homeTeam->name;
        $away = $this->game->awayTeam->name;
        $placar = "{$this->game->home_score} x {$this->game->away_score}";

        $bet = $this->game->bets()->where('user_id', $notifiable->id)->first();
        $pontos = $bet?->points_earned ?? 0;

        return (new MailMessage())
            ->subject("Resultado: {$home} {$placar} {$away}")
            ->greeting("Olá, {$notifiable->name}!")
            ->line("Saiu o resultado de {$home} x {$away}: **{$placar}**.")
            ->line("Você fez **{$pontos} ponto(s)** neste jogo.")
            ->action('Ver ranking', route('dashboard'))
            ->line('Boa sorte nos próximos jogos!');
    }
}
