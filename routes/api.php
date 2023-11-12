<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// if (auth()->guest()) {
//     auth()->login(App\Models\User::first());
// }

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login/send-verify', [LoginController::class, 'sendVerify'])->name('login.sendVerify');
        Route::post('/login/check-verify', [LoginController::class, 'checkVerify'])->name('login.checkVerify');
        Route::post('/register', [RegisterController::class, 'register'])->name('register');
        Route::post('/register/send-verify', [RegisterController::class, 'sendVerify'])->name('register.sendVerify');
        Route::post('/register/check-verify', [RegisterController::class, 'checkVerify'])->name('register.checkVerify');

        Route::middleware('auth:api')->group(function () {
            Route::get('/get-me', [ProfileController::class, 'getMe'])->name('profile.getMe');
        });
    });
});
