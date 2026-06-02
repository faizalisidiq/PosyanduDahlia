<?php

use App\Http\Controllers\GrowthMonitoringController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:anggota-kader,ketua-kader'])->group(function () {
    Route::resource('growth-monitorings', GrowthMonitoringController::class);
});
