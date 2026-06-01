<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SertifikasiController;
use App\Models\Auditor;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/run-seeder', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);

        return 'Seeder berhasil dijalankan! Isi database sudah masuk.';
    } catch (\Exception $e) {
        return 'Error: '.$e->getMessage().'<br>Line: '.$e->getLine().'<br>File: '.$e->getFile();
    }
});

Route::middleware(['auth'])->group(function () {
    // Portal
    Route::get('/portal', function () {
        return view('portal');
    })->name('portal');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pengguna
    Route::controller(\App\Http\Controllers\ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // Sertifikasi
    Route::controller(SertifikasiController::class)->prefix('sertifikasi')->name('sertifikasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/tambah', 'create')->name('create');
        Route::post('/simpan', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}/update', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/{id}/fase-2', 'createFase2')->name('fase2');
        Route::put('/{id}/fase-2/simpan', 'storeFase2')->name('storeFase2');
    });


    // Persuratan
    Route::prefix('persuratan')->name('persuratan.')->group(function () {
        Route::get('/create', function () {
            $sertifikasis = \App\Models\Sertifikasi::with('perusahaan')->get();

            return view('Persuratan.create', compact('sertifikasis'));
        })->name('create');
        Route::post('/generate', [\App\Http\Controllers\PersuratanController::class, 'generate'])->name('generate');
    });

    // Rekap Data
    Route::controller(RekapController::class)->prefix('rekap')->name('rekap.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/export', 'export')->name('export');
    });

    // API Routes (menggunakan Session Auth)
    Route::prefix('api')->group(function () {
        Route::get('/kota/{id_provinsi}', function ($id_provinsi) {
            return response()->json(\App\Models\Kota::where('id_provinsi', $id_provinsi)->orderBy('kota')->get());
        });

        Route::post('/auditor/store', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate(['nama_auditor' => 'required|string|max:255']);
            $name = trim($validated['nama_auditor']);
            if ($name && ! Auditor::where('nama_auditor', $name)->exists()) {
                Auditor::create(['nama_auditor' => $name]);
            }

            return response()->json(['ok' => true]);
        });

        Route::post('/lab/store', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate(['nama_lab' => 'required|string|max:255']);
            $name = trim($validated['nama_lab']);
            if ($name && ! \App\Models\Lab::where('nama_lab', $name)->exists()) {
                \App\Models\Lab::create(['nama_lab' => $name]);
            }

            return response()->json(['ok' => true]);
        });

        Route::post('/petugas/store', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate(['nama_petugas' => 'required|string|max:255']);
            $name = trim($validated['nama_petugas']);
            if ($name && ! \App\Models\PetugasPengambilContoh::where('nama_ppc', $name)->exists()) {
                \App\Models\PetugasPengambilContoh::create(['nama_ppc' => $name]);
            }

            return response()->json(['ok' => true]);
        });

        Route::post('/teknis/store', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate(['nama_teknis' => 'required|string|max:255']);
            $name = trim($validated['nama_teknis']);
            if ($name && ! \App\Models\TimTeknis::where('nama_teknis', $name)->exists()) {
                \App\Models\TimTeknis::create(['nama_teknis' => $name]);
            }

            return response()->json(['ok' => true]);
        });
    });
});

require __DIR__.'/auth.php';
