<?php

use App\Http\Controllers\ChildrenController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:anggota-kader,ketua-kader'])->group(function () {
    Route::get('childrens/export-growth', [ChildrenController::class, 'exportGrowth'])->name('childrens.export-growth');
    Route::get('childrens/{children}/export-history', [ChildrenController::class, 'exportHistory'])->name('childrens.export-history');
    Route::resource('childrens', ChildrenController::class);
});

Route::get('export/history/{children}', [ChildrenController::class, 'exportHistory'])
    ->name('childrens.public-export-history')
    ->middleware('signed');
