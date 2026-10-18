<?php

use App\Http\Controllers\Admin\AnalisisController;
use App\Http\Controllers\Admin\AturanController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BobotController;
use App\Http\Controllers\Admin\DestinasiController as AdminDestinasiController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\RekomendasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute aplikasi
|--------------------------------------------------------------------------
| Semua permintaan dari browser masuk lewat file ini lebih dulu.
| Urutan penulisan = urutan pencocokan URL.
|
| Rute publik  : bisa diakses siapa saja (wisatawan).
| Rute admin   : dibungkus middleware('auth'), jadi harus login.
*/

// ---------------------------------------------------------------- publik
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// POST dipakai untuk mengirim form, GET untuk menampilkan hasilnya.
// Pola ini disebut POST-Redirect-GET (lihat RekomendasiController).
Route::post('/rekomendasi', [RekomendasiController::class, 'store'])->name('rekomendasi.store');
Route::get('/rekomendasi', [RekomendasiController::class, 'show'])->name('rekomendasi.show');

Route::get('/destinasi/{kode}', [DestinasiController::class, 'show'])
    ->where('kode', 'A[0-9]{1,2}')
    ->name('destinasi.show');

// ---------------------------------------------------------------- admin
Route::prefix('admin')->name('admin.')->group(function () {

    // Login tidak boleh dibungkus middleware auth (kalau dibungkus, tidak akan pernah bisa masuk).
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', fn () => redirect()->route('admin.destinasi.index'))->name('dashboard');

        // CRUD destinasi + skor C6 per minat
        Route::resource('destinasi', AdminDestinasiController::class)->except(['show']);

        // Bobot segmen (10 baris sekaligus)
        Route::get('bobot', [BobotController::class, 'index'])->name('bobot.index');
        Route::put('bobot', [BobotController::class, 'update'])->name('bobot.update');

        // Aturan kelayakan (ambang C6 per minat)
        Route::get('aturan', [AturanController::class, 'index'])->name('aturan.index');
        Route::put('aturan', [AturanController::class, 'update'])->name('aturan.update');

        // Analisis sebaran rekomendasi
        Route::get('analisis', [AnalisisController::class, 'index'])->name('analisis.index');
    });
});
