<?php

use App\Http\Controllers\ElderlyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('elderlies', ElderlyController::class);
});
