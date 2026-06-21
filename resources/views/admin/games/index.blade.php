<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Jogos</h2>
            <a href="{{ route('admin.games.create') }}" class="br-btn-sm">Novo jogo</a>
        </div>
    </x-slot>

    @php $tz = config('bolao.display_timezone'); @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="br-alert-success">{{ session('status') }}</div>
            @endif

            <div class="br-card overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="br-thead">
                        <tr>
                            <th>Data/hora</th>
                            <th>Fase</th>
                            <th>Confronto</th>
                            <th>Placar</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="br-tbody divide-y divide-[#009C3B]/10 dark:divide-[#003A8C]">
                        @foreach ($games as $game)
                            <tr>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $game->match_datetime->copy()->setTimezone($tz)->format('d/m H:i') }}</td>
                                <td class="px-4 py-2">
                                    <span class="text-xs font-medium text-[#009C3B] dark:text-[#4DDB7A] uppercase">
                                        {{ $game->stage === 'group' ? 'Grupo '.$game->group : $game->stage }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 font-medium">{{ $game->homeTeam->code }} x {{ $game->awayTeam->code }}</td>
                                <td class="px-4 py-2">
                                    @if ($game->status === 'finished')
                                        <span class="font-bold text-[#002776] dark:text-[#FFDF00]">{{ $game->home_score }}-{{ $game->away_score }}</span>
                                        <span class="br-badge-finished ml-1">✓</span>
                                    @elseif ($game->status === 'live')
                                        <span class="br-badge-live">ao vivo</span>
                                    @else
                                        <span class="br-badge-scheduled">{{ $game->status }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right whitespace-nowrap space-x-2">
                                    <a href="{{ route('admin.games.result', $game) }}" class="br-link text-xs font-medium">Resultado</a>
                                    <a href="{{ route('admin.games.edit', $game) }}" class="br-link text-xs font-medium">Editar</a>
                                    <form method="POST" action="{{ route('admin.games.destroy', $game) }}" class="inline"
                                          onsubmit="return confirm('Remover este jogo?')">
                                        @csrf @method('DELETE')
                                        <button class="br-link-danger text-xs font-medium">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-[#002776] dark:text-[#FFDF00]">{{ $games->links() }}</div>
        </div>
    </div>
</x-app-layout>
