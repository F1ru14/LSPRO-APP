<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaboratoriumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lab')->insert([
            ['nama_lab' => 'Laboratorium Penguji A', 'keterangan' => 'Lab Utama'],
            ['nama_lab' => 'Laboratorium Penguji B', 'keterangan' => 'Lab Alternatif'],
            ['nama_lab' => 'Laboratorium Kalibrasi', 'keterangan' => 'Lab Khusus Kalibrasi'],
        ]);
    }
}
