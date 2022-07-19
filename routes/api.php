<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArtikelController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('category', [CategoryController::class, 'index']);
Route::get('category/show/{id}', [CategoryController::class, 'show']);
Route::get('artikel', [ArtikelController::class, 'index']);
Route::get('artikel/bycategory', [ArtikelController::class, 'searchByCategory']);
Route::get('artikel/byname', [ArtikelController::class, 'searchByName']);
Route::get('artikel/show/{id}', [ArtikelController::class, 'show']);

Route::post('login', [UserController::class, 'login']);

// user
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::post('users/create', [UserController::class, 'create']);
    Route::post('logout/admin', [UserController::class, 'logout']);
});

Route::middleware(['auth:api', 'role:user'])->group(function () {
    // kategori
    Route::post('category/create', [CategoryController::class, 'store']);
    Route::post('category/edit/{id}', [CategoryController::class, 'update']);
    Route::delete('category/delete/{id}', [CategoryController::class, 'destroy']);
    
    // artikel
    Route::post('artikel/create', [ArtikelController::class, 'store']);
    Route::post('artikel/edit/{id}', [ArtikelController::class, 'update']);
    Route::delete('artikel/delete/{id}', [ArtikelController::class, 'destroy']);

    Route::post('logout/user', [UserController::class, 'logout']);
});