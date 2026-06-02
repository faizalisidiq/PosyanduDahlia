<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;

Route::middleware(['auth', 'verified', 'role:ketua-kader,anggota-kader'])->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
