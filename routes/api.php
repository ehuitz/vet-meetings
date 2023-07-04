<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VeterinarianController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\BusyController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\FreeController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('veterinarians', VeterinarianController::class);
Route::apiResource('schedules', ScheduleController::class);
Route::apiResource('busies', BusyController::class);
Route::apiResource('syncs', SyncController::class);
Route::apiResource('frees', FreeController::class);




