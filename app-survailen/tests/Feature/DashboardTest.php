<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Sertifikasi;
use App\Models\Surveilans;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to create a sertifikasi with given parameters.
     */
    protected function createSertifikasi(string $kategoriName, Carbon $tglSertifikat, int $surveilansCompleted = 0)
    {
        $kategori = Kategori::firstOrCreate(['nama_kategori' => $kategoriName]);
        $sertifikasi = Sertifikasi::create([
            'no_referensi' => 'REF-'.rand(1000, 9999),
            'no_sni' => 'SNI-'.rand(1000, 9999),
            'tgl_sertifikasi' => $tglSertifikat->toDateString(),
            'status_permohonan' => 'Terbit',
            'id_kategori' => $kategori->id_kategori,
        ]);
        // attach completed surveilans periods
        for ($p = 1; $p <= $surveilansCompleted; $p++) {
            Surveilans::create([
                'id_sertifikasi' => $sertifikasi->id_sertifikasi,
                'periode' => $p,
                'tgl_pelaksanaan' => $tglSertifikat->copy()->addYears($p)->toDateString(),
            ]);
        }

        return $sertifikasi;
    }

    public function test_it_shows_surveillance_reminders_and_excludes_recertification()
    {
        // SIINAS: 5 year validity, max 4 surveilans, reminder 9 months before each
        $now = Carbon::now();
        $tglSertifikat = $now->subYears(3); // certificate issued 3 years ago
        $cert = $this->createSertifikasi('SIINAS', $tglSertifikat, 2); // 2 surveilans already done

        // Hit controller method directly
        $controller = new \App\Http\Controllers\DashboardController;
        $response = $controller->index();
        $data = $response->getData();

        $this->assertArrayHasKey('reminders', $data);
        $reminders = $data['reminders'];
        // expecting a reminder for surveilans 3 (nextPeriod = 3)
        $this->assertNotEmpty($reminders);
        $this->assertEquals('Survailen 3', $reminders[0]['label']);
        // ensure no "Re-sertifikasi" label appears
        foreach ($reminders as $r) {
            $this->assertNotEquals('Re-sertifikasi', $r['label']);
        }
    }

    public function test_it_stops_reminders_after_all_surveillance_completed()
    {
        // Non SIINAS: 4 year validity, max 3 surveilans, reminder 11 months before each
        $now = Carbon::now();
        $tglSertifikat = $now->subYears(3); // 3 years ago, within 4‑year validity
        // all 3 surveilans already done
        $cert = $this->createSertifikasi('Non SIINAS', $tglSertifikat, 3);

        $controller = new \App\Http\Controllers\DashboardController;
        $response = $controller->index();
        $data = $response->getData();
        $reminders = $data['reminders'];
        // No reminders should be generated because nextPeriod is null and we have removed re‑certification logic
        $this->assertEmpty($reminders);
    }
}
