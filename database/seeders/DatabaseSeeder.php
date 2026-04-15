<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Jarwo',
                'email' => 'supervisor@gmail.com',
                'role' => 'Supervisor',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Sopo',
                'email' => 'hsekantor@gmail.com',
                'role' => 'HSE Kantor',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Adit',
                'email' => 'hseadmin@gmail.com',
                'role' => 'HSE Admin',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Denis',
                'email' => 'hselapangan@gmail.com',
                'role' => 'HSE Lapangan',
                'password' => Hash::make('123456'),
            ],
        ]);
    }
}
