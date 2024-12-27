<?php

use App\Http\Controllers\Api\Config\CategoryController;
use App\Http\Controllers\Api\Config\ItemLocationController;
use App\Http\Controllers\Api\Config\ProductController;
use App\Http\Controllers\Api\Crm\ContactController;
use App\Models\ItemLocation;
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

Route::middleware([])->group(function () {
    Route::get('categories', CategoryController::class)->name('api.category');
    Route::get('products', ProductController::class)->name('api.product');
    Route::get('contacts', ContactController::class)->name('api.contact');
    Route::get('location', ItemLocationController::class)->name('api.item-location');
});
