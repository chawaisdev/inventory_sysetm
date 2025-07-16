<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AddUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;


// Dashboard Controller
Route::controller(DashboardController::class)->group(function () {
    Route::get('index', 'index')->name('dashboard.index');
});

// RESOURCE ROUTES
Route::resource('brands', BrandController::class);
Route::resource('adduser', AddUserController::class);
Route::resource('products', ProductController::class);
Route::resource('sales', SaleController::class);
Route::resource('purchase', PurchaseController::class);


// Purchase Controller
Route::controller(PurchaseController::class)->group(function () {
    Route::get('purchase-items', 'getPurchaseItems')->name('purchase.items');
    Route::post('purchase/return', 'return')->name('purchase.return');
    Route::get('purchase-returns', 'getPurchaseReturns')->name('purchase.return');

    Route::post('purchase/payment/{purchase}', 'storePayment')->name('purchase.payment');
});

// Sale Controller
Route::controller(SaleController::class)->group(function () {    
    Route::get('sale-items', 'getSaleItems')->name('sale.item');
});

Route::controller(PagesController::class)->group(function () {
    Route::get('report', 'salerecord')->name('report.salerecord');
    Route::get('purchase-record', 'purchaserecord')->name('sales.purchaserecord');

});
Route::get('/sales/{id}/receipt', [SaleController::class, 'receipt']);











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
