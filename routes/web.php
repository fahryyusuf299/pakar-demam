<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiagnosisController;

Route::get('/', function () {
    return view('beranda');
})->name('beranda');

Route::get('/konsultasi', [DiagnosisController::class, 'index'])->name('konsultasi.index');
Route::post('/konsultasi/proses', [DiagnosisController::class, 'proses'])->name('konsultasi.proses');
Route::get('/konsultasi/hasil/{id}', [DiagnosisController::class, 'hasil'])->name('konsultasi.hasil');
