<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/pending-approval', function () {
    return view('auth.pending');
})->middleware('auth')->name('pending.approval');
