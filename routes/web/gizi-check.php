<?php

use App\Http\Controllers\GrowthCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/layanan', function () {
    return view('public.landing');
})->name('public.landing');

// Public Routes (mirip pola /antrian — tanpa login)
Route::prefix('cek-gizi')->name('gizi.')->group(function () {
    Route::get('/', [GrowthCheckController::class, 'index'])->name('index');
    Route::post('/check', [GrowthCheckController::class, 'check'])->name('check');
    Route::get('/anak/{child}', [GrowthCheckController::class, 'show'])->name('child.show');
});