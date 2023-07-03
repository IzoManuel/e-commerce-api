<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::middleware(['auth:sanctum'])->group(function () {
});

/**ADMIN ROUTES */
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    //Products
    Route::post('/products', [ProductController::class, 'store']);

    //categories
    Route::post('/categories', [CategoryController::class, 'store']);

    //logout
    Route::post('/logout', [AuthController::class, 'adminLogout']);
});