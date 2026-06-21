<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ $team->exists ? 'Editar time' : 'Novo time' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="br-card p-6">
                @if ($errors->any())
                    <div class="br-alert-error mb-4">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ $team->exists ? route('admin.teams.update', $team) : route('admin.teams.store') }}" class="space-y-4">
                    @csrf
                    @if ($team->exists) @method('PUT') @endif

                    <div>
                        <label class="br-label">Nome</label>
                        <input type="text" name="name" value="{{ old('name', $team->name) }}" required
                            class="br-input mt-1 w-full">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="br-label">Sigla (FIFA)</label>
                            <input type="text" name="code" maxlength="3" value="{{ old('code', $team->code) }}"
                                class="br-input mt-1 w-full">
                        </div>
                        <div>
                            <label class="br-label">Grupo</label>
                            <select name="group" class="br-select mt-1 w-full">
                                <option value="">—</option>
                                @foreach (range('A', 'L') as $g)
                                    <option value="{{ $g }}" @selected(old('group', $team->group) === $g)>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="br-label">Bandeira (emoji/url)</label>
                            <input type="text" name="flag" value="{{ old('flag', $team->flag) }}"
                                class="br-input mt-1 w-full">
                        </div>
                        <div>
                            <label class="br-label">External ID (API)</label>
                            <input type="number" name="external_id" value="{{ old('external_id', $team->external_id) }}"
                                class="br-input mt-1 w-full">
                        </div>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button class="br-btn">Salvar</button>
                        <a href="{{ route('admin.teams.index') }}" class="br-btn-cancel">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
