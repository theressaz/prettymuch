<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
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
require __DIR__.'/auth.php';

Route::get('/', [ItemController::class, 'latest_3'])->name('user.home');
Route::get('/about', [ItemController::class, 'about'])->name('user.about');
Route::get('/item', [ItemController::class, 'user_item'])->name('user.item');
Route::get('/item/search', [ItemController::class, 'filtered'])->name('user.item.search');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('user.item.show');

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/cart', [CartController::class, 'show'])->name('user.cart');
    Route::post('/cart/{item}', [CartController::class, 'update'])->name('user.cart.add');
    Route::delete('/cart/cart_item/{id}', [CartItemController::class, 'destroy'])->name('user.cart.item.delete');
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('user.checkout');
    Route::get('/transaction', [TransactionController::class, 'user_past_tr'])->name('user.transaction');
    Route::post('/item/{item}/review', [ItemController::class, 'add_review'])->name('user.review.post');
});

Route::middleware(['auth', 'verified', 'is.admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/item', [ItemController::class, 'index'])->name('admin.item');
    Route::post('/admin/item', [ItemController::class, 'store'])->name('admin.item.create');
    Route::get('/admin/item/{item}/edit', [ItemController::class, 'edit'])->name('admin.item.edit');
    Route::put('/admin/item/{item}', [ItemController::class, 'update'])->name('admin.item.update');
    Route::delete('/admin/item/{item}', [ItemController::class, 'destroy'])->name('admin.item.delete');
    Route::get('/admin/transaction', [TransactionController::class, 'admin_list_all'])->name('admin.transaction');
    Route::get('/admin/transaction/{transaction}/accept', [TransactionController::class, 'accept'])->name('admin.transaction.accept');
    Route::get('/admin/transaction/{transaction}/reject', [TransactionController::class, 'reject'])->name('admin.transaction.reject');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});