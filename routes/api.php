<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return response()->json([
        'status' => false,
        'msg' => 'Silahkan login terlebih dahulu'
    ], 401);
})->name('login');

Route::post('register', [AuthController::class, 'register']);

Route::post('login-user', [AuthController::class, 'login']);

Route::get('products', [ProductController::class, 'index'])->middleware('auth:sanctum');
Route::get('products/product', [ProductController::class, 'show'])->middleware('auth:sanctum');
