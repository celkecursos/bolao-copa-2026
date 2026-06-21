<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa o cache de permissões.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'teams.manage',
            'games.manage',
            'games.set-result',
            'bets.create',
            'users.manage',
            'roles.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // super-admin: passa por cima de tudo via Gate::before (AppServiceProvider),
        // mas também recebe todas as permissões.
        $superAdmin = Role::findOrCreate('super-admin', 'web');
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::findOrCreate('admin', 'web');
        $admin->syncPermissions(['teams.manage', 'games.manage', 'games.set-result', 'bets.create']);

        $user = Role::findOrCreate('user', 'web');
        $user->syncPermissions(['bets.create']);

        // Cria o super-admin padrão a partir do .env.
        $admin = User::firstOrCreate(
            ['email' => env('SEED_SUPERADMIN_EMAIL', 'superadmin@bolao.test')],
            [
                'name' => env('SEED_SUPERADMIN_NAME', 'Super Admin'),
                'password' => Hash::make(env('SEED_SUPERADMIN_PASSWORD', 'password')),
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles('super-admin');
    }
}
