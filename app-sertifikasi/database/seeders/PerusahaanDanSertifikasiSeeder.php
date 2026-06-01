<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use App\Models\Sertifikasi;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PerusahaanDanSertifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $kategoriIds = \App\Models\Kategori::pluck('id_kategori')->toArray();
        $kotaIds = \App\Models\Kota::pluck('id_kota')->toArray();

        for ($i = 0; $i < 15; $i++) {
            $perusahaan = Perusahaan::create([
                'id_kota' => ! empty($kotaIds) ? $faker->randomElement($kotaIds) : null,
                'nama_perusahaan' => $faker->company,
                'alamat_kantor' => $faker->address,
                'telp_kantor' => $faker->phoneNumber,
                'fax_kantor' => $faker->phoneNumber,
                'alamat_pabrik' => $faker->address,
                'telp_pabrik' => $faker->phoneNumber,
                'fax_pabrik' => $faker->phoneNumber,
                'alamat_importir' => $faker->address,
                'nama_importir' => $faker->company,
                'telp_importir' => $faker->phoneNumber,
                'fax_importir' => $faker->phoneNumber,
                'email' => $faker->companyEmail,
                'contact_person' => $faker->name,
                'telp_cp' => $faker->phoneNumber,
                'merek' => ucfirst($faker->word),
                'komoditi' => ucfirst($faker->word),
                'tipe_produk' => ucfirst($faker->word),
            ]);

            Sertifikasi::create([
                'id_perusahaan' => $perusahaan->id_perusahaan,
                'no_referensi' => 'REF-'.$faker->unique()->numerify('######'),
                'id_kategori' => ! empty($kategoriIds) ? $faker->randomElement($kategoriIds) : null,
                'tgl_permohonan' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
            ]);
        }
    }
}
