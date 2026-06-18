<?php

use App\Http\Controllers\Api\ChildrenApiController;
use App\Http\Controllers\Api\IotMeasurementController;
use Illuminate\Support\Facades\Route;

Route::get('/childrens', [ChildrenApiController::class, 'index']);
Route::post('/childrens', [ChildrenApiController::class, 'store']);
Route::get('/childrens/{id}', [ChildrenApiController::class, 'show']);

Route::prefix('iot')->group(function () {
    Route::post('/measurement', [IotMeasurementController::class, 'store']);
    Route::get('/measurement/latest', [IotMeasurementController::class, 'latest']);
});
