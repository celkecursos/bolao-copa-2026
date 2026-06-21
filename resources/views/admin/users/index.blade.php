<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Usuários e papéis</h2>
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
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Papel atual</th>
                            <th>Alterar papel</th>
                        </tr>
                    </thead>
                    <tbody class="br-tbody divide-y divide-[#009C3B]/10 dark:divide-[#003A8C]">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 font-medium">{{ $user->name }}</td>
                                <td class="px-4 py-2 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                <td class="px-4 py-2">
                                    @foreach ($user->roles as $role)
                                        <span class="inline-block text-xs font-bold px-2 py-0.5 rounded-full
                                            @if($role->name === 'super-admin') bg-[#FFDF00] text-[#002776]
                                            @elseif($role->name === 'admin') bg-[#009C3B] text-white
                                            @else bg-[#002776]/10 dark:bg-[#002776]/40 text-[#002776] dark:text-[#FFDF00]
                                            @endif">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                    @if ($user->roles->isEmpty()) <span class="text-gray-400">—</span> @endif
                                </td>
                                <td class="px-4 py-2">
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex items-center gap-2">
                                        @csrf @method('PUT')
                                        <select name="role" class="br-select text-sm">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role }}" @selected($user->hasRole($role))>{{ $role }}</option>
                                            @endforeach
                                        </select>
                                        <button class="br-btn-sm text-xs">Aplicar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-[#002776] dark:text-[#FFDF00]">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
