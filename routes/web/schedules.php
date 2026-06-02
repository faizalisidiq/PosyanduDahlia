<?php

use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
});
