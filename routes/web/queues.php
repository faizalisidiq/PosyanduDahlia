<?php

use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;

// Public Routes (Kiosk/Mothers)
Route::group(['prefix' => 'antrian', 'as' => 'queues.public.'], function () {
    Route::get('/', [QueueController::class, 'kiosk'])->name('index'); // Input form
    Route::post('/check', [QueueController::class, 'check'])->name('check'); // Check child
    Route::post('/store', [QueueController::class, 'storePublic'])->name('store'); // Get number
    Route::get('/tickets', [QueueController::class, 'tickets'])->name('tickets'); // Show multiple tickets
    Route::get('/{queue}/ticket', [QueueController::class, 'ticket'])->name('ticket'); // Show number
    Route::get('/monitor', [QueueController::class, 'monitor'])->name('monitor'); // Monitor TV
    Route::get('/monitor/data', [QueueController::class, 'data'])->name('data'); // JSON Data
});

// Protected Routes (Staff)
Route::middleware(['auth', 'role:anggota-kader,ketua-kader'])->group(function () {
    Route::get('queues', [QueueController::class, 'index'])->name('queues.index');
    Route::patch('queues/{queue}/status', [QueueController::class, 'updateStatus'])->name('queues.status');
});
