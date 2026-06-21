<?php

namespace App\Services;

use App\Models\Bet;
use App\Models\Game;

class ScoringService
{
    /**
     * Pontos de um palpite contra o placar real.
     * Regras NÃO acumulam — usa-se a maior aplicável.
     */
    public function points(int $betHome, int $betAway, int $gameHome, int $gameAway): int
    {
        $cfg = config('bolao.scoring');

        // Placar exato (já inclui o acerto do vencedor).
        if ($betHome === $gameHome && $betAway === $gameAway) {
            return (int) $cfg['exact'];
        }

        // Acertou o vencedor / empate.
        if ($this->sign($betHome - $betAway) === $this->sign($gameHome - $gameAway)) {
            return (int) $cfg['winner'];
        }

        // Acerto parcial: gols de exatamente um dos times.
        if ($cfg['partial_enabled'] && ($betHome === $gameHome || $betAway === $gameAway)) {
            return (int) $cfg['partial'];
        }

        return 0;
    }

    public function pointsForBet(Bet $bet, Game $game): int
    {
        if (! $game->hasResult()) {
            return 0;
        }

        return $this->points($bet->home_score, $bet->away_score, $game->home_score, $game->away_score);
    }

    /**
     * Recalcula (de forma idempotente) os pontos de TODOS os palpites de um jogo.
     * Define o valor absoluto — rodar N vezes dá sempre o mesmo resultado.
     */
    public function recalculateGame(Game $game): void
    {
        $game->loadMissing('bets');

        foreach ($game->bets as $bet) {
            $points = $this->pointsForBet($bet, $game);

            if ($bet->points_earned !== $points) {
                $bet->points_earned = $points;
                $bet->save();
            }
        }
    }

    private function sign(int $n): int
    {
        return $n <=> 0;
    }
}
