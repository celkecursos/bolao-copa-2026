<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pontuação (Task 6)
    |--------------------------------------------------------------------------
    | Regras NÃO acumulam — usa-se a maior aplicável.
    */
    'scoring' => [
        'exact' => 10,            // acertou o placar exato
        'winner' => 5,            // acertou o vencedor/empate (placar errado)
        'partial' => 1,           // acertou os gols de apenas um dos times
        'partial_enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Palpites (Task 5)
    |--------------------------------------------------------------------------
    | Minutos antes do início do jogo em que o palpite trava.
    */
    'bet_lock_buffer_minutes' => 5,

    /*
    |--------------------------------------------------------------------------
    | Exibição
    |--------------------------------------------------------------------------
    | Datas são salvas em UTC e exibidas neste fuso.
    */
    'display_timezone' => 'America/Sao_Paulo',
];

// A config da API football-data.org fica em config/services.php ('football_data').
