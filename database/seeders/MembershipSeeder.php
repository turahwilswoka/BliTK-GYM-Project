<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket Silver',
                'description' => 'Akses gym 1 bulan penuh, cocok untuk pemula',
                'duration_days' => 30,
                'price' => 150000,
                'features' => ['Akses gym unlimited', 'Loker gratis', 'Air minum gratis'],
                'badge_color' => '#C0C0C0',
                'is_active' => true,
            ],
            [
                'name' => 'Paket Gold',
                'description' => 'Akses gym 3 bulan, hemat lebih banyak',
                'duration_days' => 90,
                'price' => 400000,
                'features' => ['Akses gym unlimited', 'Loker gratis', 'Air minum gratis', 'Handuk gratis', 'Personal training 1x'],
                'badge_color' => '#FFD700',
                'is_active' => true,
            ],
            [
                'name' => 'Paket Platinum',
                'description' => 'Akses gym 1 tahun penuh, nilai terbaik',
                'duration_days' => 365,
                'price' => 1200000,
                'features' => ['Akses gym unlimited', 'Loker gratis', 'Air minum gratis', 'Handuk gratis', 'Personal training 4x', 'Akses kelas zumba & yoga', 'Prioritas booking'],
                'badge_color' => '#E5E4E2',
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            Membership::firstOrCreate(['name' => $package['name']], $package);
        }
    }
}
