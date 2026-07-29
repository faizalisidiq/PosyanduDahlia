<?php

use App\Http\Controllers\Api\ChildrenApiController;
use App\Http\Controllers\Api\IotMeasurementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SuhuReadingController;
use App\Http\Controllers\Api\TensiStagingController;

Route::get('/childrens', [ChildrenApiController::class, 'index']);
Route::post('/childrens', [ChildrenApiController::class, 'store']);
Route::get('/childrens/{id}', [ChildrenApiController::class, 'show']);
Route::post('/vitals/suhu', [SuhuReadingController::class, 'store']);
Route::post('/vitals/tensi', [TensiStagingController::class, 'store']);
Route::get('/vitals/tensi/latest', [TensiStagingController::class, 'latest']);

Route::prefix('iot')->group(function () {
    Route::post('/measurement', [IotMeasurementController::class, 'store']);
    Route::get('/measurement/latest', [IotMeasurementController::class, 'latest']);
});
