<?php

use App\Http\Controllers\PregnancyRecordController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('pregnancy-records', PregnancyRecordController::class);
});
