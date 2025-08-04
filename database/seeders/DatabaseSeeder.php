<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();

        User::insert([
            [
                'name' => 'Supervisor',
                'email' => 'sup@oxygen.test',
                'password' => Hash::make('sup-pass'),
                'role' => UserRole::Supervisor,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Technician 1',
                'email' => 'tech1@oxygen.test',
                'password' => Hash::make('tech1-pass'),
                'role' => UserRole::Technician,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Technician 2',
                'email' => 'tech2@oxygen.test',
                'password' => Hash::make('tech2-pass'),
                'role' => UserRole::Technician,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
