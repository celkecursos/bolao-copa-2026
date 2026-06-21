<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Lançar resultado</h2>
    </x-slot>

    @php $tz = config('bolao.display_timezone'); @endphp

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Formulário de resultado --}}
            <div class="br-card overflow-hidden">
                <div class="px-6 py-4 bg-[#009C3B] dark:bg-[#002776]">
                    <div class="text-center text-white dark:text-[#FFDF00] font-bold text-lg">
                        {!! $game->homeTeam->flagDisplay() !!} {{ $game->homeTeam->name }}
                        <span class="mx-2 opacity-70">x</span>
                        {{ $game->awayTeam->name }} {!! $game->awayTeam->flagDisplay() !!}
                    </div>
                    <div class="text-center text-xs text-white/70 dark:text-[#FFDF00]/70 mt-1">
                        {{ $game->match_datetime->copy()->setTimezone($tz)->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="p-6">
                    @if ($errors->any())
                        <div class="br-alert-error mb-4">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.games.result.store', $game) }}" class="flex items-center justify-center gap-4">
                        @csrf @method('PUT')
                        <input type="number" name="home_score" min="0" max="99" required
                            value="{{ old('home_score', $game->home_score) }}"
                            class="br-input w-20 text-center text-2xl font-bold">
                        <span class="text-[#009C3B] dark:text-[#FFDF00] text-2xl font-black">×</span>
                        <input type="number" name="away_score" min="0" max="99" required
                            value="{{ old('away_score', $game->away_score) }}"
                            class="br-input w-20 text-center text-2xl font-bold">
                        <button class="br-btn">Salvar resultado</button>
                    </form>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-4 text-center">
                        Ao salvar, a pontuação de todos os palpites é recalculada (em fila).
                    </p>
                </div>
            </div>

            {{-- Histórico de auditoria --}}
            <div class="br-card overflow-hidden">
                <div class="px-6 py-3 bg-[#009C3B]/10 dark:bg-[#002776]/50 border-b border-[#009C3B]/20 dark:border-[#003A8C]">
                    <h3 class="br-section-title text-base">Histórico de auditoria</h3>
                </div>
                <div class="p-6">
                    @forelse ($game->audits as $audit)
                        <div class="text-xs border-b border-[#009C3B]/10 dark:border-[#003A8C] py-2">
                            <div class="text-gray-500 dark:text-gray-400 font-medium">
                                {{ $audit->created_at->copy()->setTimezone($tz)->format('d/m/Y H:i') }}
                                · <span class="text-[#009C3B] dark:text-[#4DDB7A]">{{ $audit->user->name ?? 'sistema' }}</span>
                                · {{ $audit->event }}
                            </div>
                            <div class="text-gray-700 dark:text-gray-300 mt-0.5">
                                @foreach ($audit->getModified() as $field => $changes)
                                    <span class="mr-3">
                                        <span class="text-[#002776] dark:text-[#FFDF00]/80 font-medium">{{ $field }}</span>:
                                        <em class="text-red-500">{{ $changes['old'] ?? '—' }}</em>
                                        → <strong class="text-[#009C3B] dark:text-[#4DDB7A]">{{ $changes['new'] ?? '—' }}</strong>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Sem alterações registradas.</p>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('admin.games.index') }}" class="br-link text-sm">← Voltar aos jogos</a>
        </div>
    </div>
</x-app-layout>
