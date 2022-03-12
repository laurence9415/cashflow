<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy']);
    Route::get('/cashflows/total-cashflow', App\Http\Controllers\API\TotalCashflowController::class);
    Route::get('/cashflows/total', App\Http\Controllers\API\OverallTotalCashflowController::class);
    Route::get('/cashflows/summary', App\Http\Controllers\API\CashflowSummaryController::class);
    Route::apiResource('cashflows', App\Http\Controllers\Api\CashflowController::class);
});
