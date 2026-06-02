<?php

use App\Http\Controllers\HealthPostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('health-posts', HealthPostController::class);
});