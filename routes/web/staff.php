<?php

use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:ketua-kader'])->group(function () {
    Route::resource('staffs', StaffController::class);
    Route::patch('staffs/{staff}/approve', [StaffController::class, 'approve'])->name('staffs.approve');
});
