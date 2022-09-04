<?php

use App\Http\Controllers\ReservationCancellationsController;
use App\Http\Controllers\ReservationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('reservations')->group(function () {
    Route::post('/', [ReservationsController::class, 'store']);
    Route::get('/', [ReservationsController::class, 'index']);

    Route::post('/{id}/cancel', [ReservationCancellationsController::class, 'store']);
});
