<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReaderController;
use Illuminate\Http\Request;
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

//authentication routes
Route::post('login', [AuthController::class, 'login'])->name('api.login');
Route::post('register', [AuthController::class, 'register'])->name('api.register');
Route::middleware('auth:sanctum')->get('user', [AuthController::class, 'user']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout'])->name('api.logout');

//secured routes
Route::group(['middleware' => 'auth:sanctum'], function() {
    //book routes
    Route::group(['prefix' => 'books'], function() {
        Route::get('/', [BookController::class, 'index']);
        Route::post('/', [BookController::class, 'store']);
        Route::get('/{id}', [BookController::class, 'show']);
        Route::post('/{id}', [BookController::class, 'update']);
        Route::delete('/{id}', [BookController::class, 'destroy']);
    });
    // booking routes
    Route::group(['prefix' => 'bookings'], function() {
        Route::get('/', [BookingController::class, 'index']);
        Route::post('/', [BookingController::class, 'store']);
        Route::get('/{id}', [BookingController::class, 'show']);
        Route::post('/{id}', [BookingController::class, 'update']);
    });

    Route::get('genres', [BookController::class, 'genres']);

    //readers routes
    Route::group(['prefix' => 'readers'], function() {
        Route::get('/', [ReaderController::class, 'index']);

    });

});

