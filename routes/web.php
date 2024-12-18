<?php

use App\Livewire\Config\ProductSetting\Branch;
use App\Livewire\Config\ProductSetting\Category;
use App\Livewire\Config\ProductSetting\Product;
use App\Livewire\Config\ProductSetting\SubCategory;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('config/branch', Branch::class)->name('branch');
    Route::get('config/category', Category::class)->name('category');
    Route::get('config/sub-category', SubCategory::class)->name('sub-category');
    Route::get('config/product', Product::class)->name('product');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
