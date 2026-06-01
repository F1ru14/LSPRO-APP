<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SurveilansController;
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
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pengguna
    Route::controller(\App\Http\Controllers\ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });


    // Surveilans
    Route::controller(SurveilansController::class)->prefix('surveilans')->name('surveilans.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/tambah', 'create')->name('create');
        Route::post('/simpan', 'store')->name('store');
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
        Route::get('/sertifikasi/{no_referensi}', [SurveilansController::class, 'getDataBySertifikasi'])->where('no_referensi', '.*');
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

// Redirect to Sertifikasi App for login
Route::get('/login', function () {
    return redirect('http://sertifikasi.localhost/login');
})->name('login');
