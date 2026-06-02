<?php

use App\Http\Controllers\IlpScreeningController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:anggota-kader,ketua-kader'])->group(function () {
    Route::get('ilp-screenings/export', [IlpScreeningController::class, 'export'])->name('ilp-screenings.export');
    Route::resource('ilp-screenings', IlpScreeningController::class);
});
