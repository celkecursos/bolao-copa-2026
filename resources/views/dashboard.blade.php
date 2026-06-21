<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php $tz = config('bolao.display_timezone'); @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Cards de resumo --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="br-card p-6 border-l-4 border-t-0 border-[#FFDF00]">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Meus pontos</div>
                    <div class="text-3xl font-bold text-[#FFDF00] mt-1">{{ $myPoints }}</div>
                </div>
                <div class="br-card p-6 border-l-4 border-t-0 border-[#009C3B]">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Palpites feitos</div>
                    <div class="text-3xl font-bold text-[#009C3B] dark:text-[#4DDB7A] mt-1">{{ $myBetsCount }}</div>
                </div>
                <div class="br-card p-6 border-l-4 border-t-0 border-[#002776] dark:border-[#FFDF00]">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Minha posição</div>
                    <div class="text-3xl font-bold text-[#002776] dark:text-[#FFDF00] mt-1">
                        {{ $current?->rank ? '#'.$current->rank : '—' }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Ranking Top 10 --}}
                <div class="br-card overflow-hidden">
                    <div class="px-6 py-4 bg-[#009C3B] dark:bg-[#002776]">
                        <h3 class="font-bold text-lg text-white dark:text-[#FFDF00]">🏆 Ranking — Top 10</h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-[#009C3B] dark:text-[#FFDF00] border-b-2 border-[#009C3B]/20 dark:border-[#FFDF00]/30">
                                    <th class="pb-2 w-10">#</th>
                                    <th class="pb-2">Jogador</th>
                                    <th class="pb-2 text-right">Pontos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top as $row)
                                    <tr class="border-b border-[#009C3B]/10 dark:border-[#003A8C] {{ $current && $row->id === $current->id ? 'bg-[#FFFDE7] dark:bg-[#FFDF00]/10 font-semibold' : 'hover:bg-[#F0FAF4] dark:hover:bg-[#002050]' }} transition-colors">
                                        <td class="py-2 text-[#009C3B] dark:text-[#4DDB7A] font-bold">{{ $row->rank }}</td>
                                        <td class="py-2 text-gray-800 dark:text-gray-200">{{ $row->name }}</td>
                                        <td class="py-2 text-right font-semibold text-[#002776] dark:text-[#FFDF00]">{{ $row->total_points }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($current && $current->rank > 10)
                            <div class="mt-3 pt-3 border-t-2 border-[#009C3B]/20 dark:border-[#FFDF00]/30 text-sm flex justify-between font-bold text-[#002776] dark:text-[#FFDF00]">
                                <span>#{{ $current->rank }} {{ $current->name }} (você)</span>
                                <span>{{ $current->total_points }} pts</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Jogos do dia --}}
                <div class="br-card overflow-hidden">
                    <div class="px-6 py-4 bg-[#009C3B] dark:bg-[#002776]">
                        <h3 class="font-bold text-lg text-white dark:text-[#FFDF00]">📅 Jogos de hoje</h3>
                    </div>
                    <div class="p-6">
                        @forelse ($todayGames as $game)
                            @php $bet = $myBets->get($game->id); @endphp
                            <div class="flex items-center justify-between py-2 border-b border-[#009C3B]/10 dark:border-[#003A8C] text-sm">
                                <div class="text-gray-800 dark:text-gray-200">
                                    {!! $game->homeTeam->flagDisplay() !!} {{ $game->homeTeam->code }}
                                    <span class="text-gray-400 mx-1">x</span>
                                    {{ $game->awayTeam->code }} {!! $game->awayTeam->flagDisplay() !!}
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $game->match_datetime->copy()->setTimezone($tz)->format('H:i') }}
                                        @if ($bet)
                                            · <span class="text-[#009C3B] dark:text-[#4DDB7A] font-medium">palpite: {{ $bet->home_score }}-{{ $bet->away_score }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if ($game->status === 'finished')
                                        <span class="font-bold text-[#002776] dark:text-white">{{ $game->home_score }} - {{ $game->away_score }}</span>
                                        <div class="br-badge-finished">encerrado ✓</div>
                                    @elseif ($game->status === 'live')
                                        <span class="br-badge-live">ao vivo 🔴</span>
                                    @else
                                        <span class="br-badge-scheduled">agendado</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 py-2">Nenhum jogo hoje.</p>
                        @endforelse

                        @if ($nextGames->isNotEmpty())
                            <h4 class="br-subsection-title mt-5 mb-2">Próximos jogos</h4>
                            @foreach ($nextGames as $game)
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 py-1">
                                    <span>{{ $game->homeTeam->code }} x {{ $game->awayTeam->code }}</span>
                                    <span class="text-[#002776] dark:text-[#FFDF00] font-medium">{{ $game->match_datetime->copy()->setTimezone($tz)->format('d/m H:i') }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
