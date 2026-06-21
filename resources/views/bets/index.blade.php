<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Meus Palpites
        </h2>
    </x-slot>

    @php $tz = config('bolao.display_timezone'); @endphp

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status'))
                <div class="br-alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="br-alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="br-card divide-y divide-[#009C3B]/10 dark:divide-[#003A8C]">
                @foreach ($games as $game)
                    @php $bet = $myBets->get($game->id); $open = $game->isBettingOpen(); @endphp
                    <div class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-[#F0FAF4] dark:hover:bg-[#002050] transition-colors">
                        <div class="text-sm">
                            <div class="text-xs font-semibold text-[#009C3B] dark:text-[#4DDB7A] uppercase tracking-wide">
                                {{ $game->stage === 'group' ? 'Grupo '.$game->group : $game->stage }}
                                · {{ $game->match_datetime->copy()->setTimezone($tz)->format('d/m H:i') }}
                            </div>
                            <div class="text-gray-800 dark:text-gray-200 font-medium mt-0.5">
                                {!! $game->homeTeam->flagDisplay() !!} {{ $game->homeTeam->name }}
                                <span class="text-gray-400 mx-1">x</span>
                                {{ $game->awayTeam->name }} {!! $game->awayTeam->flagDisplay() !!}
                            </div>
                            @if ($game->status === 'finished')
                                <div class="text-xs mt-1">
                                    <span class="text-[#002776] dark:text-[#FFDF00] font-semibold">
                                        Resultado: {{ $game->home_score }} - {{ $game->away_score }}
                                    </span>
                                    @if ($bet)
                                        · <span class="text-[#009C3B] dark:text-[#4DDB7A] font-bold">{{ $bet->points_earned }} pts</span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @if ($open)
                            <form method="POST" action="{{ route('bets.store', $game) }}" class="flex items-center gap-2">
                                @csrf
                                <input type="number" name="home_score" min="0" max="99" required
                                    value="{{ $bet->home_score ?? '' }}"
                                    class="br-input w-14 text-center">
                                <span class="text-[#009C3B] dark:text-[#4DDB7A] font-bold text-lg">x</span>
                                <input type="number" name="away_score" min="0" max="99" required
                                    value="{{ $bet->away_score ?? '' }}"
                                    class="br-input w-14 text-center">
                                <button class="br-btn-sm">Salvar</button>
                            </form>
                        @else
                            <div class="text-sm text-right">
                                @if ($bet)
                                    <span class="text-gray-700 dark:text-gray-300">Seu palpite: <strong class="text-[#002776] dark:text-[#FFDF00]">{{ $bet->home_score }} - {{ $bet->away_score }}</strong></span>
                                @else
                                    <span class="text-gray-400 italic">Sem palpite</span>
                                @endif
                                <div class="text-xs text-gray-400 mt-0.5">prazo encerrado</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
