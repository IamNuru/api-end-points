<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use \App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TodolistController;
use App\Models\Todo;



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
Route::get('related/products/{id}', [ProductController::class, 'relatedProducts']);
Route::get('address/{id}', [AddressController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('category/{id}', [CategoryController::class, 'show']);
Route::get('products/category/{catName}', [CategoryController::class, 'categoryProducts']);
Route::get('products/homepage/{catName}', [ProductController::class, 'homepageProducts']);
Route::get('category/products/{id}', [ProductController::class, 'categoryProducts']);
Route::get('search', [ProductController::class, 'search']);
Route::get('checkorderstatus/{orderNumber}', [TransactionController::class, 'checkOrderStatus']);
Route::get('cart/items/{cart}', [ProductController::class, 'cartItems']);
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset']);


/**
 * the following routes are todos routes
 * 
 * START
 */
Route::prefix('todo')->group(function () {
    Route::post('login/user', [OwnerController::class, 'check']);
    Route::post('register/user', [OwnerController::class, 'store']);
    Route::post('user/{id}', [OwnerController::class, 'update']);
    //Todo Items
    Route::get('todos/{id}', [TodoController::class, 'todos']);
    Route::get('todo/{id}', [TodoController::class, 'show']);
    Route::delete('todo/{id}', [TodoController::class, 'destroy']);
    Route::post('additem/{id}', [TodoController::class, 'store']);
    Route::post('updatetodo/{id}', [TodoController::class, 'update']);
    Route::post('updatestatus/{id}', [TodoController::class, 'updatestatus']);
    //todolists
    Route::get('todolists/{id}', [TodolistController::class, 'todolists']);
    Route::get('todolist/{id}', [TodolistController::class, 'show']);
    Route::delete('todolist/{id}', [TodolistController::class, 'destroy']);
    Route::post('addtodolist', [TodolistController::class, 'store']);
    Route::post('updatetodolist/{id}', [TodolistController::class, 'update']);
});

/**
 * END TODO ROUTES
 */








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
