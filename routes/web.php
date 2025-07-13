<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AddUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;


// Dashboard Controller
Route::controller(DashboardController::class)->group(function () {
    Route::get('index', 'index')->name('dashboard.index');
});

// Brand Controller
Route::resource('brands', BrandController::class);

// Add User Controller
Route::resource('adduser', AddUserController::class);

// Product Controller
Route::resource('products', ProductController::class);

// Purchase Controller
Route::controller(PurchaseController::class)->group(function () {
    Route::get('purchase-items', 'getPurchaseItems')->name('purchase.items');
    Route::get('purchase/return', 'return')->name('purchase.return');
    Route::post('purchase/payment/{purchase}', 'storePayment')->name('purchase.payment');
});
Route::resource('purchase', PurchaseController::class);

// Sale Controller
Route::controller(SaleController::class)->group(function () {
    Route::get('get-products-by-brand/{brand_id}', 'getProductsByBrand')->name('sales.products.by.brand');
    Route::get('get-product-details/{id}', 'getProductDetails')->name('sales.product.details');
});
Route::resource('sales', SaleController::class);











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
