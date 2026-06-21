<?php

namespace App\Notifications;

use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BetReminderNotification extends Notification implements ShouldQueue
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
        $tz = config('bolao.display_timezone');
        $hora = $this->game->match_datetime->copy()->setTimezone($tz)->format('d/m H:i');

        return (new MailMessage())
            ->subject("Não esqueça do seu palpite: {$home} x {$away}")
            ->greeting("Olá, {$notifiable->name}!")
            ->line("O jogo {$home} x {$away} começa em breve ({$hora}) e você ainda não palpitou.")
            ->action('Fazer meu palpite', route('bets.index'))
            ->line('Corra antes que o prazo feche!');
    }
}
