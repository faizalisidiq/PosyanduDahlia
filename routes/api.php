<?php

use App\Http\Controllers\Api\ChildrenApiController;
use Illuminate\Support\Facades\Route;

Route::get('/childrens', [ChildrenApiController::class, 'index']);
Route::post('/childrens', [ChildrenApiController::class, 'store']);
Route::get('/childrens/{id}', [ChildrenApiController::class, 'show']);
