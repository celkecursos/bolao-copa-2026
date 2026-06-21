<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Importa/atualiza jogos e resultados reais durante o torneio (a cada 15 min).
Schedule::command('worldcup:sync-matches')->everyFifteenMinutes();

// Lembra quem ainda não palpitou em jogos próximos.
Schedule::command('matches:send-reminders')->hourly();
