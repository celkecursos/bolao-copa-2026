<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameRequest;
use App\Http\Requests\GameResultRequest;
use App\Jobs\RecalculateGamePointsJob;
use App\Models\Game;
use App\Models\Team;
use App\Notifications\ResultPostedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $games = Game::with(['homeTeam', 'awayTeam'])
            ->when($request->filled('group'), fn ($q) => $q->where('group', $request->group))
            ->when($request->filled('stage'), fn ($q) => $q->where('stage', $request->stage))
            ->orderBy('match_datetime')
            ->paginate(20)
            ->withQueryString();

        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.form', [
            'game' => new Game(['stage' => 'group', 'status' => 'scheduled']),
            'teams' => Team::orderBy('name')->get(),
        ]);
    }

    public function store(GameRequest $request): RedirectResponse
    {
        Game::create($this->normalized($request));

        return redirect()->route('admin.games.index')->with('status', 'Jogo cadastrado!');
    }

    /** Interpreta a data informada no fuso de exibição e salva em UTC. */
    private function normalized(GameRequest $request): array
    {
        $data = $request->validated();
        $data['match_datetime'] = Carbon::parse($data['match_datetime'], config('bolao.display_timezone'))->utc();

        return $data;
    }

    public function edit(Game $game)
    {
        return view('admin.games.form', [
            'game' => $game,
            'teams' => Team::orderBy('name')->get(),
        ]);
    }

    public function update(GameRequest $request, Game $game): RedirectResponse
    {
        $game->update($this->normalized($request));

        return redirect()->route('admin.games.index')->with('status', 'Jogo atualizado!');
    }

    public function destroy(Game $game): RedirectResponse
    {
        $game->delete();

        return back()->with('status', 'Jogo removido!');
    }

    /** Tela de lançamento de resultado (+ histórico de auditoria). */
    public function resultForm(Game $game)
    {
        $game->load(['homeTeam', 'awayTeam', 'audits.user']);

        return view('admin.games.result', compact('game'));
    }

    /** Salva o resultado, dispara o recálculo de pontos e notifica os apostadores. */
    public function storeResult(GameResultRequest $request, Game $game): RedirectResponse
    {
        $game->update([
            'home_score' => $request->integer('home_score'),
            'away_score' => $request->integer('away_score'),
            'status' => 'finished',
        ]);

        // Recálculo idempotente em fila.
        RecalculateGamePointsJob::dispatch($game->id);

        // Notifica (em fila) os usuários que palpitaram neste jogo.
        $bettors = $game->bets()->with('user')->get()->pluck('user')->filter();
        if ($bettors->isNotEmpty()) {
            Notification::send($bettors, new ResultPostedNotification($game));
        }

        return redirect()->route('admin.games.index')
            ->with('status', 'Resultado lançado e pontuação recalculada!');
    }
}
