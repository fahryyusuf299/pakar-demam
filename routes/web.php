<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPenyakitController;
use App\Http\Controllers\Admin\AdminGejalaController;
use App\Http\Controllers\Admin\AdminRuleController;
use App\Http\Controllers\Admin\AdminRiwayatController;

// Public Client Routes
Route::get('/', function () {
    return view('beranda');
})->name('beranda');

Route::get('/konsultasi', [DiagnosisController::class, 'index'])->name('konsultasi.index');
Route::post('/konsultasi/proses', [DiagnosisController::class, 'proses'])->name('konsultasi.proses');
Route::get('/konsultasi/hasil/{id}', [DiagnosisController::class, 'hasil'])->name('konsultasi.hasil');

// Admin Panel Routes
Route::prefix('admin')->group(function () {
    // Auth Rute
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Protected Admin Routes (Guard: admin)
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/logs', [AdminDashboardController::class, 'logs'])->name('admin.logs');

        // CRUD Penyakit
        Route::get('/penyakit', [AdminPenyakitController::class, 'index'])->name('admin.penyakit.index');
        Route::post('/penyakit', [AdminPenyakitController::class, 'store'])->name('admin.penyakit.store');
        Route::put('/penyakit/{id}', [AdminPenyakitController::class, 'update'])->name('admin.penyakit.update');
        Route::delete('/penyakit/{id}', [AdminPenyakitController::class, 'destroy'])->name('admin.penyakit.destroy');

        // CRUD Gejala
        Route::get('/gejala', [AdminGejalaController::class, 'index'])->name('admin.gejala.index');
        Route::post('/gejala', [AdminGejalaController::class, 'store'])->name('admin.gejala.store');
        Route::put('/gejala/{id}', [AdminGejalaController::class, 'update'])->name('admin.gejala.update');
        Route::delete('/gejala/{id}', [AdminGejalaController::class, 'destroy'])->name('admin.gejala.destroy');

        // CRUD Rules
        Route::get('/rules', [AdminRuleController::class, 'index'])->name('admin.rules.index');
        Route::get('/rules/{id}', [AdminRuleController::class, 'show'])->name('admin.rules.show');
        Route::post('/rules', [AdminRuleController::class, 'store'])->name('admin.rules.store');
        Route::delete('/rules/{id}', [AdminRuleController::class, 'destroy'])->name('admin.rules.destroy');

        // Riwayat Konsultasi
        Route::get('/riwayat', [AdminRiwayatController::class, 'index'])->name('admin.riwayat.index');
        Route::get('/riwayat/{id}', [AdminRiwayatController::class, 'show'])->name('admin.riwayat.show');
        Route::delete('/riwayat/{id}', [AdminRiwayatController::class, 'destroy'])->name('admin.riwayat.destroy');
    });
});
