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

// Rute publik wisatawan
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::post('/rekomendasi', [RekomendasiController::class, 'store'])->name('rekomendasi.store');
Route::get('/rekomendasi', [RekomendasiController::class, 'show'])->name('rekomendasi.show');

Route::get('/destinasi', [DestinasiController::class, 'index'])->name('destinasi.index');
Route::get('/destinasi/{kode}', [DestinasiController::class, 'show'])
    ->where('kode', 'A[0-9]{1,2}')
    ->name('destinasi.show');

// Rute panel administrator
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', fn () => redirect()->route('admin.destinasi.index'))->name('dashboard');

        Route::resource('destinasi', AdminDestinasiController::class)->except(['show']);

        Route::get('bobot', [BobotController::class, 'index'])->name('bobot.index');
        Route::put('bobot', [BobotController::class, 'update'])->name('bobot.update');

        Route::get('aturan', [AturanController::class, 'index'])->name('aturan.index');
        Route::put('aturan', [AturanController::class, 'update'])->name('aturan.update');

        Route::get('analisis', [AnalisisController::class, 'index'])->name('analisis.index');
    });
});
