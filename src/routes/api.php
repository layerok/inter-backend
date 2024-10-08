<?php

use App\Http\Controllers\AuthController;
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

Route::prefix('auth')->group(static function (): void {
    Route::get('csrf-cookie', [AuthController::class, 'initializeCsrfCookie']);
    Route::post('login/browser', [AuthController::class, 'loginBrowser']);
    Route::middleware('sanctum')->group(function() {
        Route::get('user', [AuthController::class, 'getAuthenticatedUser']);
        Route::post('logout/browser', [AuthController::class, 'logoutBrowser']);
    });
});

Route::apiResource('user', \App\Http\Controllers\UserController::class);
