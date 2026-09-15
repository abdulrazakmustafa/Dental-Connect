<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * RolesAndPermissionsSeeder and CatalogueSeeder are safe/idempotent
     * baseline data. DemoDataSeeder is non-production sample data (PRD §100)
     * — never run this seeder set automatically against production.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            CatalogueSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
