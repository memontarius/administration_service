<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoleAndPermissionSeeder::class,
        ]);

        $this->executeGenerationClientOfPassportCommand();
    }

    /**
     * @return void
     */
    public function executeGenerationClientOfPassportCommand(): void
    {
        $parameters = [
            '--personal' => true,
            '--name' => 'Central Panel Personal Access Client',
        ];

        Artisan::call('passport:client', $parameters);
    }
}
