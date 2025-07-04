<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\productController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [productController::class, 'showProducts'])->name('products');

Route::get('/signup', [SignUpController::class, 'showSignUpForm'])->name('signup');
// Route::post('signup', [SignUpController::class, 'registerUser']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('login', [LoginController::class, 'loginUser']);

Route::get('/cart', [CartController::class, 'showCart'])->name('cart');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/radio/update/{id}', [CartController::class, 'updateRadio'])->name('cart.radio.update');

Route::post('/review/{id}', [productController::class, 'review'])->name('review');
Route::post('/placeOrder{total}', [CartController::class, 'placeOrder'])->name('placeOrder');

Route::get('/orders', [OrderController::class, 'showOrder'])->name('orders');
Route::get('/tracking/{id}', [OrderController::class, 'showTracking'])->name('tracking');

Route::get('admin_products', [AdminProductController::class, 'showAdminProducts'])->name('admin/products');
Route::post('/product/add', [AdminProductController::class, 'productAdd'])->name('product.add');
Route::post('/product/update/{id}', [AdminProductController::class, 'productUpdate'])->name('product.update');
Route::get('/product/delete/{id}', [AdminProductController::class, 'productDelete'])->name('product.delete');

Route::get('/product/search', [productController::class, 'search'])->name('product.search');

// Route::get('logout', [LoginController::class, 'logout'])->name('logout');
