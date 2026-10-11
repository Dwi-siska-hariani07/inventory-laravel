<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes (Pertemuan 5 - Slide 12, 15, 16, 18)
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard
// Authentication Routes persis P4
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Halaman-Halaman Internal (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Produk
    Route::resource('products', ProductController::class);

    // Kategori & Supplier (Persis P4)
    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/suppliers', [App\Http\Controllers\SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [App\Http\Controllers\SupplierController::class, 'store'])->name('suppliers.store');
    Route::delete('/suppliers/{supplier}', [App\Http\Controllers\SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // Transaksi Stok Masuk & Keluar (Business Logic P4)
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/history', [StockController::class, 'history'])->name('history');
        Route::get('/in', [StockController::class, 'createIn'])->name('in');
        Route::post('/in', [StockController::class, 'storeIn'])->name('storeIn');
        Route::get('/out', [StockController::class, 'createOut'])->name('out');
        Route::post('/out', [StockController::class, 'storeOut'])->name('storeOut');
    });

    // Administrasi Pengguna (Hanya Admin)
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
});
