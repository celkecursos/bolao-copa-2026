<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ $game->exists ? 'Editar jogo' : 'Novo jogo' }}
        </h2>
    </x-slot>

    @php $tz = config('bolao.display_timezone'); @endphp

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="br-card p-6">
                @if ($errors->any())
                    <div class="br-alert-error mb-4">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ $game->exists ? route('admin.games.update', $game) : route('admin.games.store') }}" class="space-y-4">
                    @csrf
                    @if ($game->exists) @method('PUT') @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="br-label">Mandante</label>
                            <select name="home_team_id" required class="br-select mt-1 w-full">
                                @foreach ($teams as $t)
                                    <option value="{{ $t->id }}" @selected(old('home_team_id', $game->home_team_id) == $t->id)>{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="br-label">Visitante</label>
                            <select name="away_team_id" required class="br-select mt-1 w-full">
                                @foreach ($teams as $t)
                                    <option value="{{ $t->id }}" @selected(old('away_team_id', $game->away_team_id) == $t->id)>{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="br-label">Grupo</label>
                            <select name="group" class="br-select mt-1 w-full">
                                <option value="">—</option>
                                @foreach (range('A', 'L') as $g)
                                    <option value="{{ $g }}" @selected(old('group', $game->group) === $g)>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="br-label">Fase</label>
                            <select name="stage" required class="br-select mt-1 w-full">
                                @foreach (\App\Models\Game::STAGES as $s)
                                    <option value="{{ $s }}" @selected(old('stage', $game->stage) === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="br-label">Status</label>
                            <select name="status" required class="br-select mt-1 w-full">
                                @foreach (['scheduled', 'live', 'finished'] as $s)
                                    <option value="{{ $s }}" @selected(old('status', $game->status) === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="br-label">Data/hora (horário de Brasília)</label>
                        <input type="datetime-local" name="match_datetime" required
                            value="{{ old('match_datetime', optional($game->match_datetime)->copy()?->setTimezone($tz)->format('Y-m-d\TH:i')) }}"
                            class="br-input mt-1 w-full">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="br-label">Estádio</label>
                            <input type="text" name="stadium" value="{{ old('stadium', $game->stadium) }}"
                                class="br-input mt-1 w-full">
                        </div>
                        <div>
                            <label class="br-label">Cidade</label>
                            <input type="text" name="city" value="{{ old('city', $game->city) }}"
                                class="br-input mt-1 w-full">
                        </div>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button class="br-btn">Salvar</button>
                        <a href="{{ route('admin.games.index') }}" class="br-btn-cancel">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
