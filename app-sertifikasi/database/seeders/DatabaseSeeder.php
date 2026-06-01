<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default administrator user — strictly read from .env, no fallback defaults
        $adminEmail = env('ADMIN_INITIAL_EMAIL');
        $adminUsername = env('ADMIN_INITIAL_USERNAME');
        $adminName = env('ADMIN_INITIAL_NAME');
        $adminPassword = env('ADMIN_INITIAL_PASSWORD');

        if (empty($adminEmail) || empty($adminUsername) || empty($adminName) || empty($adminPassword)) {
            throw new \RuntimeException(
                'Seeder dihentikan: variabel ADMIN_INITIAL_EMAIL, ADMIN_INITIAL_USERNAME, '.PHP_EOL.
                'ADMIN_INITIAL_NAME, dan ADMIN_INITIAL_PASSWORD wajib diisi di berkas .env sebelum menjalankan db:seed.'
            );
        }

        User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'username' => $adminUsername,
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
                'level_user' => 1,
            ]
        );

        $this->call([
            WilayahSeeder::class,
            KategoriSeeder::class,
            AuditorSeeder::class,
            LaboratoriumSeeder::class,
            PerusahaanDanSertifikasiSeeder::class,
        ]);
    }
}
