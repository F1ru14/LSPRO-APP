<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Kategori::firstOrCreate(['nama_kategori' => 'SIINAS']);
        \App\Models\Kategori::firstOrCreate(['nama_kategori' => 'Non SIINAS']);
        \App\Models\Kategori::firstOrCreate(['nama_kategori' => 'Luar Negeri']);
    }
}
