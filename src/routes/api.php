<?php

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

Route::post('/', [App\Http\Controllers\StockController::class, 'store']);
Route::get('/product/{productId}', [App\Http\Controllers\StockController::class, 'index']);
Route::get('/{id}', [App\Http\Controllers\StockController::class, 'edit']);
Route::put('/{id}', [App\Http\Controllers\StockController::class, 'update']);
Route::delete('/{id}', [App\Http\Controllers\StockController::class, 'destroy']);

Route::fallback(function() {
	return response()->json(['message' => 'Not Found.'], 404);
})->name('api.fallback.404');