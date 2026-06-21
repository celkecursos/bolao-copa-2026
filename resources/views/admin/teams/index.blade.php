<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Times</h2>
            <a href="{{ route('admin.teams.create') }}" class="br-btn-sm">Novo time</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="br-alert-success">{{ session('status') }}</div>
            @endif

            <div class="br-card overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="br-thead">
                        <tr>
                            <th>Grupo</th>
                            <th>Time</th>
                            <th>Sigla</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="br-tbody divide-y divide-[#009C3B]/10 dark:divide-[#003A8C]">
                        @foreach ($teams as $team)
                            <tr>
                                <td class="px-4 py-2">
                                    <span class="inline-block w-6 h-6 rounded-full bg-[#009C3B] dark:bg-[#FFDF00] text-white dark:text-[#002776] text-xs font-bold text-center leading-6">
                                        {{ $team->group }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 font-medium">{!! $team->flagDisplay() !!} {{ $team->name }}</td>
                                <td class="px-4 py-2">
                                    <span class="text-xs font-bold text-[#002776] dark:text-[#FFDF00] bg-[#002776]/10 dark:bg-[#FFDF00]/10 px-2 py-0.5 rounded">{{ $team->code }}</span>
                                </td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <a href="{{ route('admin.teams.edit', $team) }}" class="br-link text-xs font-medium">Editar</a>
                                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}" class="inline"
                                          onsubmit="return confirm('Remover este time?')">
                                        @csrf @method('DELETE')
                                        <button class="br-link-danger text-xs font-medium">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-[#002776] dark:text-[#FFDF00]">{{ $teams->links() }}</div>
        </div>
    </div>
</x-app-layout>
