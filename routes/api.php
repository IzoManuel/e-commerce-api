<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Categories\CategoryController;
use App\Http\Controllers\Api\Products\ProductController;
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

/**AUTH ROUTES */
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/login', [LoginController::class, 'login']);

Route::post('/admin/login', [LoginController::class, 'adminLogin']);

/**PRODUCT ROUTES*/
Route::get('/products', [ProductController::class, 'index']);


/**ADMIN ROUTES */
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    //Products
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    //categories
    Route::post('/categories', [CategoryController::class, 'store']);

    //logout
    Route::post('/logout', [LoginController::class, 'adminLogout']);
});