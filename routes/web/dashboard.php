<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:anggota-kader,ketua-kader'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
});
