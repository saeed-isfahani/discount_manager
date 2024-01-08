<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\PasswordController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\ShopController;
use App\Http\Controllers\Api\V1\ProvinceCityController;
use App\Http\Controllers\Api\V1\CategoriesController;
use App\Http\Controllers\Api\V1\Auth\UserController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RoleController;
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
            Route::get('/get-permissions', [ProfileController::class, 'getPermissions'])->name('profile.getPermissions');
            Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');
            Route::patch('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::post('/password/set', [PasswordController::class, 'set'])->name('password.set');
            Route::post('/password/remove', [PasswordController::class, 'remove'])->name('password.remove');
        });
    });
    Route::middleware('auth:api')->group(function () {
        Route::post('shops/{shop}/logo', [ShopController::class, 'shopLogo']);
        Route::post('/users/{user}/active', [UserController::class, 'activateUser'])->name('user.active');
        Route::post('/users/{user}/deactive', [UserController::class, 'deactivateUser'])->name('user.deactive');
        Route::patch('shops/{shop}/activate', [ShopController::class, 'activateShop']);
        Route::patch('shops/{shop}/deactivate', [ShopController::class, 'deactivateShop']);
        Route::get('shops/shop-count', [ShopController::class, 'shopCount'])->name('shops.shopcount');
        Route::get('shops/shop-count-by-category', [ShopController::class, 'shopCountByCategory'])->name('shops.shopcountbycategory');
        Route::get('shops/shop-count-by-month', [ShopController::class, 'shopCountByMonth'])->name('shops.shopcountbymonth');
        Route::get('shops/{shop}/products', [ShopController::class, 'shopProducts']);
        Route::apiResource('shops', ShopController::class);
        Route::apiResource('categories', CategoriesController::class);
        Route::apiResource('users', UserController::class);
        Route::get('permissions', [PermissionController::class, 'index']);
        Route::get('roles/users', [RoleController::class, 'usersWithRoles']);
        Route::patch('roles/{role}/assign-permission', [RoleController::class, 'assignPermission']);
        Route::apiResource('roles', RoleController::class);
        Route::get('products/most-visited', [ProductController::class, 'mostVisited'])->name('products.mostvisited');
        Route::apiResource('products', ProductController::class);
    });
    Route::get('/provinces', [ProvinceCityController::class, 'provincesList'])->name('provinces.list');
    Route::get('/cities/{province}', [ProvinceCityController::class, 'citiesList'])->name('cities.list');
});
