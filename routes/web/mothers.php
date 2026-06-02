<?php

use App\Http\Controllers\MotherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:anggota-kader,ketua-kader'])->group(function () {
    Route::get('mothers/export', [MotherController::class, 'export'])->name('mothers.export');
    Route::patch('mothers/{mother}/status', [MotherController::class, 'updateStatus'])->name('mothers.update-status');
    Route::resource('mothers', MotherController::class);
});
