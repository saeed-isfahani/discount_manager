<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\PasswordController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\ShopController;
use App\Http\Controllers\Api\V1\ProvinceCityController;
use App\Http\Controllers\Api\V1\CategoriesController;
use App\Http\Controllers\Api\V1\Auth\UserController;
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


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login/send-verify', [LoginController::class, 'sendVerify'])->name('login.sendVerify');
        Route::post('/login/check-verify', [LoginController::class, 'checkVerify'])->name('login.checkVerify');
        Route::post('/login/check-password', [LoginController::class, 'checkPassword'])->name('login.checkPassword');
        Route::post('/register', [RegisterController::class, 'register'])->name('register');
        Route::post('/register/send-verify', [RegisterController::class, 'sendVerify'])->name('register.sendVerify');
        Route::post('/register/check-verify', [RegisterController::class, 'checkVerify'])->name('register.checkVerify');
        Route::middleware('auth:api')->group(function () {
            Route::get('/get-me', [ProfileController::class, 'getMe'])->name('profile.getMe');
            Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');
            Route::patch('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        });
    });
    Route::middleware('auth:api')->group(function () {
        Route::resource('shops', ShopController::class)->only(['index', 'store', 'show', 'update',]);
        Route::post('shops/{shop}/logo', [ShopController::class, 'logo']);
        Route::resource('categories', CategoriesController::class);
        Route::post('/password/set', [PasswordController::class, 'set'])->name('password.set');
        Route::post('/password/remove', [PasswordController::class, 'remove'])->name('password.remove');
        Route::get('/users', [UserController::class, 'index'])->name('user.index');
        Route::post('/users/{user}/active', [UserController::class, 'active'])->name('user.active');
        Route::post('/users/{user}/deactive', [UserController::class, 'deactive'])->name('user.deactive');
    });
    Route::get('/provinces', [ProvinceCityController::class, 'provincesList'])->name('provinces.list');
    Route::get('/cities/{province}', [ProvinceCityController::class, 'citiesList'])->name('cities.list');
});
