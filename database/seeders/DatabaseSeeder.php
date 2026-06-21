<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seeder de DESENVOLVIMENTO (migrate:fresh --seed).
     * Usa jogos fictícios do WorldCup2026Seeder (sem API).
     *
     * Para PRODUÇÃO use: php artisan db:seed --class=ProductionSeeder --force
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            WorldCup2026Seeder::class,
            DemoBetsSeeder::class,
        ]);
    }
}
