<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;



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


Route::middleware([
    'auth:sanctum',
])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user_info', [AuthController::class, 'userinfo']);

});

Route::get('/product_index', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'product']);
Route::post('/categorias', [ProductController::class, 'categorias']);
Route::post('/referencias', [ProductController::class, 'referencias']);
Route::post('/product_create', [ProductController::class, 'store']);
Route::post('/product_update/{id}', [ProductController::class, 'actualizar']);
Route::delete('product_delete/{id}', [ProductController::class, 'eliminar']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);