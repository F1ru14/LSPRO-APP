<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('auditor')->insert([
            ['nama_auditor' => 'Budi Santoso', 'jabatan' => 'Lead Auditor'],
            ['nama_auditor' => 'Andi Wijaya', 'jabatan' => 'Auditor'],
            ['nama_auditor' => 'Siti Aminah', 'jabatan' => 'Auditor'],
        ]);
    }
}
