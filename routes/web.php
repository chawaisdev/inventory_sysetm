<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AddUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;


Route::resource('brands', BrandController::class);
Route::resource('adduser', AddUserController::class);
Route::resource('products', ProductController::class);
Route::resource('brands', BrandController::class);
Route::resource('purchase', PurchaseController::class);
Route::get('purchase-items', [PurchaseController::class, 'getPurchaseItems'])->name('purchase.items');
Route::get('index', [DashboardController::class, 'index'])->name('dashboard.index');
Route::resource('sales', SaleController::class);
Route::get('/get-products-by-brand/{brand_id}', [SaleController::class, 'getProductsByBrand']);
Route::get('/get-product-details/{id}', [SaleController::class, 'getProductDetails']);













Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
