<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Reading local wilayah data...');
        $jsonPath = database_path('data/wilayah.json');

        if (! file_exists($jsonPath)) {
            $this->command->error("Data file not found at {$jsonPath}. Please provide the JSON data.");

            return;
        }

        $jsonContent = file_get_contents($jsonPath);
        $provinces = json_decode($jsonContent, true);

        // Clear existing data to avoid duplicates if run multiple times
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kota')->truncate();
        DB::table('provinsi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($provinces as $prov) {
            $this->command->info("Inserting province: {$prov['name']}");
            $provId = DB::table('provinsi')->insertGetId([
                'provinsi' => $prov['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $regencies = $prov['regencies'];
            $insertCities = [];
            foreach ($regencies as $reg) {
                $insertCities[] = [
                    'id_provinsi' => $provId,
                    'kota' => $reg['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (count($insertCities) > 0) {
                DB::table('kota')->insert($insertCities);
            }
        }

        $this->command->info('Wilayah data seeded successfully from local JSON!');
    }
}
