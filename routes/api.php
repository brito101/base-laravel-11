<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\JWTAuthController;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->name('api.')->group(function () {

    Route::post('register', [JWTAuthController::class, 'register'])->name('register');
    Route::post('login', [JWTAuthController::class, 'login'])->name('login');

    Route::middleware([JWTMiddleware::class])->group(function () {

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::get('profile', [UserController::class, 'profile'])->name('profile');
        });

        Route::post('refresh', [JWTAuthController::class, 'refresh'])->name('refresh');
        Route::post('logout', [JWTAuthController::class, 'logout'])->name('logout');
    });
});
