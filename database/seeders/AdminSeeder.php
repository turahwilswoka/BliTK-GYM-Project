<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@blitk.gym'],
            [
                'name' => 'Admin BliTK',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '08123456789',
            ]
        );
    }
}
