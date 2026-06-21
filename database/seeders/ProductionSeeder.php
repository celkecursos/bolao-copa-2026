<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class ProductionSeeder extends Seeder
{
    /**
     * Seeder de produção:
     * 1. Roles, permissões e super-admin (via .env SEED_SUPERADMIN_*)
     * 2. Jogos e times REAIS importados da API football-data.org
     * 3. Usuários demo com palpites nos jogos reais (para popular o ranking)
     *
     * Uso: php artisan db:seed --class=ProductionSeeder --force
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $this->command->info('Sincronizando jogos reais da API football-data.org...');
        Artisan::call('worldcup:sync-matches');
        $this->command->line(trim(Artisan::output()));

        $this->call(DemoBetsSeeder::class);
    }
}
