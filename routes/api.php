<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use \App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;


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

// Public routes goes below
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('products', [ProductController::class, 'index']);
Route::get('product/{id}', [ProductController::class, 'show']);
Route::get('related/products/{categoryId}', [ProductController::class, 'relatedProducts']);
Route::get('address/{id}', [AddressController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('category/{id}', [CategoryController::class, 'show']);
Route::get('products/category/{catName}', [CategoryController::class, 'categoryProducts']);
Route::get('products/homepage/{catName}', [ProductController::class, 'homepageProducts']);
Route::get('category/products/{id}', [ProductController::class, 'categoryProducts']);
Route::get('search', [ProductController::class, 'search']);
Route::get('checkorderstatus/{orderNumber}', [TransactionController::class, 'checkOrderStatus']);



// Private routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', [UserController::class, 'index']);
    Route::post('resetpassword', [UserController::class, 'resetpassword']);
    Route::post('category', [CategoryController::class, 'store']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('order', [TransactionController::class, 'store']);
    Route::get('orders', [TransactionController::class, 'index']);
    Route::get('order/{id}', [TransactionController::class, 'show']);
    Route::post('category/{id}', [CategoryController::class, 'update']);
    Route::post('product', [ProductController::class, 'store']);
    Route::post('product/{id}', [ProductController::class, 'update']);
    Route::delete('product/{id}', [ProductController::class, 'destroy']);
    Route::delete('category/{id}', [CategoryController::class, 'destroy']);
    Route::post('category/{id}', [CategoryController::class, 'update']);
});




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
