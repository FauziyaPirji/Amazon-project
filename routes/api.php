<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;

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

Route::group(['middleware'=>'api'],function($routes){
    Route::post('/addProduct',[ProductController::class, 'addProduct']);

    Route::get('/delete/{id}',[ProductController::class, 'deleteProduct']);

    Route::post('/edit/{id}',[ProductController::class, 'editProduct']);

    Route::get('/products',[ProductController::class, 'fetchProducts']);

    Route::get('/products/name',[ProductController::class, 'fetchProductName']);

    Route::get('/search/{ProductName}',[ProductController::class, 'fetchSearchProducts']);

    Route::post('/addToCartSession/{id}',[ProductController::class, 'addProductToCartWithSession']);

    Route::post('/placeOrderSession/{total}',[ProductController::class, 'placeOrderWithSession']);

    Route::post('/addToCartTable/{id}',[ProductController::class, 'addProductToCartWithTable']);

    Route::get('/totalQuantity',[ProductController::class, 'totalQty']);

    Route::get('/cart',[ProductController::class, 'showCart']);

    Route::put('/cart/updateQuantity/{id}',[ProductController::class, 'updateQuantity']);

    Route::put('/cart/updateDeliveryOption/{id}/{value}',[ProductController::class, 'updateDeliveryOption']);

    Route::delete('/cart/deleteProduct/{id}', [ProductController::class, 'deleteCartProduct']);

    Route::post('/placeOrder/{total}',[ProductController::class, 'placeOrder']);

    Route::get('/orders',[ProductController::class, 'showOrders']);

    Route::get('/tracking/{id}',[ProductController::class, 'tracking']);
});

Route::group(['middleware'=>'api'],function($routes){
    Route::post('/register', [UserController::class, 'register']);

    Route::post('/login', [UserController::class, 'login']);

    Route::get('/logout', [UserController::class, 'logout']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
