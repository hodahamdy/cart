<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\productController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {

});

Route::prefix('product')->group(function () {
    Route::get('/index', [productController::class, 'index']);
    Route::post('/store', [productController::class, 'store']);
    Route::post('/update/{id}', [productController::class, 'update']);
    Route::get('/delete/{id}', [productController::class, 'delete']);
});
Route::prefix('cart')->group(function () {
    Route::post('/add', [CartController::class, 'addToCart']);
    Route::post('/remove', [CartController::class, 'removeFromCart']);
    Route::post('/update', [CartController::class, 'updateCart']);
    Route::get('/view', [CartController::class, 'viewCart']);
});
// Registration Route
Route::post('/register', [RegisteredUserController::class, 'store']);

// Login Route
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Optional: Logout Route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');
