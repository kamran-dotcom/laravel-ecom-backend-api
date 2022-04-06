<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\CategoryController;

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

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::get('/frontend-categories',[CategoryController::class,'frontendCategories']);
Route::get('/frontend-products',[ProductController::class,'frontendProducts']);
Route::get('/category-products/{slug}',[ProductController::class,'categoryProducts']);
Route::get('/single-product/{slug}',[ProductController::class,'singleProduct']);

Route::post('/store-category',[CategoryController::class,'storeCategory']);
Route::get('/view-category',[CategoryController::class,'index']);
Route::get('/edit-category/{id}',[CategoryController::class,'edit']);
Route::put('/update-category/{id}',[CategoryController::class,'update']);
Route::delete('/delete-category/{id}',[CategoryController::class,'delete']);

// add to cart
Route::post('/add-to-cart',[CartController::class,'addToCart']);
Route::get('/cart',[CartController::class,'viewCart']);
Route::put('/update-cart/{id}/{scop}',[CartController::class,'updateCart']);
Route::delete('/delete-cart/{id}',[CartController::class,'deleteCart']);

// checkout
Route::post('/place-order',[CheckoutController::class,'placeOrder']);
// orders
Route::get('/get-orders',[CheckoutController::class,'getOrders']);

Route::get('/all-categories',[CategoryController::class,'getCategories']);
// Route::post('/store-products',[ProductController::class,'storeProduct']);
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/store-products',[ProductController::class,'storeProduct']);
    Route::get('/view-products',[ProductController::class,'index']);
    Route::delete('/delete-product/{id}',[ProductController::class,'delete']);
    Route::get('/edit-product/{id}',[ProductController::class,'edit']);
    Route::post('/update-product/{id}',[ProductController::class,'update']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
