<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('group')->orderBy('name')->paginate(20);

        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.form', ['team' => new Team()]);
    }

    public function store(TeamRequest $request): RedirectResponse
    {
        Team::create($request->validated());

        return redirect()->route('admin.teams.index')->with('status', 'Time cadastrado!');
    }

    public function edit(Team $team)
    {
        return view('admin.teams.form', compact('team'));
    }

    public function update(TeamRequest $request, Team $team): RedirectResponse
    {
        $team->update($request->validated());

        return redirect()->route('admin.teams.index')->with('status', 'Time atualizado!');
    }

    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();

        return back()->with('status', 'Time removido!');
    }
}
