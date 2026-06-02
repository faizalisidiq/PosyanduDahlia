<?php

use App\Http\Controllers\ChildbirthRecordController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:anggota-kader,ketua-kader'])->group(function () {
    Route::get('childbirth-records/export', [ChildbirthRecordController::class, 'export'])->name('childbirth-records.export');
    Route::resource('childbirth-records', ChildbirthRecordController::class);
});
