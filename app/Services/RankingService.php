<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RankingService
{
    /**
     * Ranking completo, ordenado por pontos e critério de desempate:
     * (1) total de pontos, (2) placares exatos, (3) acertos de vencedor, (4) registro.
     * Cada item recebe a posição (rank).
     */
    public function ranking(): Collection
    {
        $exact = (int) config('bolao.scoring.exact');
        $winner = (int) config('bolao.scoring.winner');

        $rows = DB::table('users')
            ->leftJoin('bets', 'bets.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COALESCE(SUM(bets.points_earned), 0) as total_points'),
                DB::raw("SUM(CASE WHEN bets.points_earned = {$exact} THEN 1 ELSE 0 END) as exact_count"),
                DB::raw("SUM(CASE WHEN bets.points_earned >= {$winner} THEN 1 ELSE 0 END) as winner_count"),
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_points')
            ->orderByDesc('exact_count')
            ->orderByDesc('winner_count')
            ->orderBy('users.id')
            ->get();

        return $rows->values()->map(function ($row, $index) {
            $row->rank = $index + 1;
            $row->total_points = (int) $row->total_points;
            $row->exact_count = (int) $row->exact_count;
            $row->winner_count = (int) $row->winner_count;

            return $row;
        });
    }

    /**
     * Top N + a linha do usuário informado (mesmo fora do top).
     *
     * @return array{top: Collection, current: ?object}
     */
    public function topAndCurrent(?int $userId, int $limit = 10): array
    {
        $ranking = $this->ranking();

        return [
            'top' => $ranking->take($limit),
            'current' => $userId ? $ranking->firstWhere('id', $userId) : null,
        ];
    }
}
