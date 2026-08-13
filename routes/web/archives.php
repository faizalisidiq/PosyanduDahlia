<?php

use App\Http\Controllers\ArchiveController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:ketua-kader'])->group(function () {
    Route::get('archives', [ArchiveController::class, 'index'])->name('archives.index');
    Route::post('archives/{type}/{id}/restore', [ArchiveController::class, 'restore'])->name('archives.restore');
    Route::delete('archives/{type}/{id}/force-delete', [ArchiveController::class, 'forceDelete'])->name('archives.force-delete');
});
