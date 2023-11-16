<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Categories\CategoryController;
use App\Http\Controllers\Api\Customer\CustomerController;
use App\Http\Controllers\Api\Payment\MpesaSTKPUSHController;
use App\Http\Controllers\Api\Payment\PayPalController;
use App\Http\Controllers\Api\Payment\StripeController;
use App\Http\Controllers\Api\Products\CheckoutController;
use App\Http\Controllers\Api\Products\OrderController;
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

/**CATEGORY ROUTES */
Route::get('/categories', [CategoryController::class, 'index']);

/**CHECKOUT FOR GUEST */
Route::post('/checkout', [CheckoutController::class, 'checkout']);

/**PAYPAL */
Route::controller(PayPalController::class)->prefix('paypal')->group(function () {
    Route::get('/handle-payment', 'handlePayment')->name('make.payment');
    Route::get('/cancel-payment', 'paymentCancel')->name('cancel.payment');
    Route::get('/payment-success', 'paymentSuccess')->name('success.payment');
    Route::post('/webhook', 'handleWebhook')->name('paypal.handleWebhook');
});

/**STRIPE */
ROUTE::controller(StripeController::class)->prefix('stripe')->group(function () {
    Route::post('/handle-payment', 'handlePayment')->name('stripe.payment');
    Route::post('/webhook', 'handleWebhook')->name('stripe.handleWebhook');
});


/**MPESASTKPUSH */
ROUTE::controller(MpesaSTKPUSHController::class)->group(function () {
    Route::post('/mpesatest/stk/push', 'STKPush')->name('mpesa.push');
    Route::post('/confirm', 'STKConfirm')->name('mpesa.confirm');
});

/**ADMIN ROUTES */
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    //Products
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    //categories
    //Route::apiResource('categories', CategoryController::class);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    //customers
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::post('/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

    //orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
    //logout
    Route::post('/logout', [LoginController::class, 'adminLogout']);
});