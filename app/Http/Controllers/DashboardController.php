<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\Game;
use App\Services\RankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request, RankingService $ranking)
    {
        $user = $request->user();
        $tz = config('bolao.display_timezone');

        // Janela "hoje" no fuso de exibição, convertida para UTC.
        $start = Carbon::now($tz)->startOfDay()->utc();
        $end = Carbon::now($tz)->endOfDay()->utc();

        $todayGames = Game::with(['homeTeam', 'awayTeam'])
            ->whereBetween('match_datetime', [$start, $end])
            ->orderBy('match_datetime')
            ->get();

        // Palpites do usuário para os jogos de hoje (indexados por game_id).
        $myBets = Bet::where('user_id', $user->id)
            ->whereIn('game_id', $todayGames->pluck('id'))
            ->get()
            ->keyBy('game_id');

        $rank = $ranking->topAndCurrent($user->id, 10);

        $nextGames = Game::with(['homeTeam', 'awayTeam'])
            ->where('match_datetime', '>', now())
            ->orderBy('match_datetime')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'top' => $rank['top'],
            'current' => $rank['current'],
            'todayGames' => $todayGames,
            'myBets' => $myBets,
            'nextGames' => $nextGames,
            'myPoints' => (int) ($rank['current']->total_points ?? 0),
            'myBetsCount' => Bet::where('user_id', $user->id)->count(),
        ]);
    }
}
