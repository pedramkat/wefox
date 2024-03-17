<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\V1\BookController;
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

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->prefix('v1')->group(function () {
    Route::get('/books', [BookController::class, 'index'])->name('booksIndex');
    Route::get('/book/{book:sku}', [BookController::class, 'show'])->name('bookShow');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/book/create', [BookController::class, 'store'])->name('bookStore');
        Route::put('/book/update/{book:sku}', [bookController::class, 'update'])->name('bookUpdate');
        Route::delete('/book/delete/{book:sku}', [bookController::class, 'delete'])->name('bookDelete');
    });
});
