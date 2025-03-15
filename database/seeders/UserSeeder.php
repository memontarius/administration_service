<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(User::getTableName())->truncate();

        User::factory()->create([
            'name' => 'admin',
            'login' => 'admin',
            'last_name' => '',
            'password' => Hash::make('admin'),
        ]);

        User::factory(49)->create();
    }
}
