<?php

namespace App\Http\Controllers;

use App\Http\Requests\BetRequest;
use App\Models\Bet;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BetController extends Controller
{
    /** Lista de jogos para palpitar, com o palpite atual do usuário. */
    public function index(Request $request)
    {
        $games = Game::with(['homeTeam', 'awayTeam'])
            ->orderBy('match_datetime')
            ->get();

        $myBets = Bet::where('user_id', $request->user()->id)
            ->get()
            ->keyBy('game_id');

        return view('bets.index', compact('games', 'myBets'));
    }

    /** Cria ou atualiza o palpite do usuário para um jogo. */
    public function store(BetRequest $request, Game $game): RedirectResponse
    {
        // Trava de prazo — validada no back-end com o horário do servidor.
        if (! $game->isBettingOpen()) {
            throw ValidationException::withMessages([
                'home_score' => 'O prazo para palpitar neste jogo já encerrou.',
            ]);
        }

        $home = (int) $request->integer('home_score');
        $away = (int) $request->integer('away_score');

        // Mata-mata não admite empate no palpite.
        if ($game->isKnockout() && $home === $away) {
            throw ValidationException::withMessages([
                'home_score' => 'Em jogos de mata-mata o palpite não pode terminar empatado.',
            ]);
        }

        Bet::updateOrCreate(
            ['user_id' => $request->user()->id, 'game_id' => $game->id],
            ['home_score' => $home, 'away_score' => $away]
        );

        return back()->with('status', 'Palpite salvo!');
    }
}
