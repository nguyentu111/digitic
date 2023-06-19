<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Carbon\CarbonImmutable;
date_default_timezone_set('Asia/Ho_Chi_Minh');
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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('products', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::put('products/{id}', [ProductController::class, 'update'])->whereNumber('id');
Route::get('products/{id}', [ProductController::class, 'show'])->whereNumber('id');
Route::delete('products/{id}', [ProductController::class, 'destroy'])->whereNumber('id');
Route::put('products/restore_all', [ProductController::class, 'restoreAll']);
Route::post('products/add_tags', [ProductController::class, 'addTags']);

Route::post('product_details', [ProductDetailController::class, 'store']);
Route::get('product_details/{id}', [ProductDetailController::class, 'show'])->whereNumber('id');
Route::put('product_details/{id}', [ProductDetailController::class, 'update'])->whereNumber('id');
Route::delete('product_details/{id}', [ProductDetailController::class, 'destroy'])->whereNumber('id');

Route::get('orders', [OrderController::class, 'index']);
Route::get('orders/{id}', [OrderController::class, 'show'])->whereNumber('id');
Route::post('orders', [OrderController::class, 'store']);
Route::put('orders/{id}', [OrderController::class, 'update'])->whereNumber('id');


Route::get('tags', [TagController::class, 'index']);
Route::post('tags', [TagController::class, 'store']);
Route::put('tags/{id}', [TagController::class, 'update'])->whereNumber('id');
Route::get('tags/{id}', [TagController::class, 'show'])->whereNumber('id');
Route::delete('tags/{id}', [TagController::class, 'destroy'])->whereNumber('id');

Route::get('colors', [ColorController::class, 'index']);
Route::post('colors', [ColorController::class, 'store']);
Route::put('colors/{id}', [ColorController::class, 'update'])->whereNumber('id');
Route::get('colors/{id}', [ColorController::class, 'show'])->whereNumber('id');
Route::delete('colors/{id}', [ColorController::class, 'destroy'])->whereNumber('id');


Route::get('sizes', [SizeController::class, 'index']);
Route::post('sizes', [SizeController::class, 'store']);
Route::get('sizes/{id}', [SizeController::class, 'show'])->whereNumber('id');
Route::delete('sizes/{id}', [SizeController::class, 'destroy'])->whereNumber('id');

Route::post('pictures',[PictureController::class,'store']);
Route::delete('pictures/{id}',[PictureController::class,'destroy'])->whereNumber('id');

Route::get('collections', [CollectionController::class, 'index']);
Route::post('collections', [CollectionController::class, 'store']);
Route::put('collections/{id}', [CollectionController::class, 'update'])->whereNumber('id');
Route::delete('collections/{id}', [CollectionController::class, 'destroy'])->whereNumber('id');

Route::get('users', [UserController::class, 'index']);
Route::post('users', [UserController::class, 'store']);
Route::put('users/{id}', [UserController::class, 'update'])->whereNumber('id');
Route::get('users/{id}', [UserController::class, 'show'])->whereNumber('id');
Route::delete('users/{id}', [UserController::class, 'destroy'])->whereNumber('id');

Route::post('sales', [SaleController::class, 'store']);
// Route::get('sales', [SaleController::class, 'show']);
Route::put('sales/{id}', [SaleController::class, 'update'])->whereNumber('id');
Route::delete('sales/{id}', [SaleController::class, 'destroy'])->whereNumber('id');


Route::post('reviews', [ReviewController::class, 'store']);
Route::put('reviews/{id}', [ReviewController::class, 'update'])->whereNumber('id');
Route::get('reviews/{id}', [ReviewController::class, 'show'])->whereNumber('id');
Route::delete('reviews/{id}', [ReviewController::class, 'destroy'])->whereNumber('id');
Route::get('user_reviews/{id}', [ReviewController::class, 'getUserReviews'])->whereNumber('id');

Route::get('time',fn()=> date('Y-m-d H:i:s', time()));
Route::get('test',function (Request $request){
    return CarbonImmutable::now()->setTimezone('Asia/Ho_Chi_Minh');
});
