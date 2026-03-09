<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SellerOrderController;
use App\Http\Controllers\SellerReportController;
use Illuminate\Support\Facades\Route;

// Landing
Route::get('/', function () {
    return view('landing');
});

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Google OAuth
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');

// Student / Teacher Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/preorder',  [PreOrderController::class, 'menuList'])->name('preorder');
    Route::post('/preorder', [PreOrderController::class, 'storeOrder'])->name('preorder.store');
    Route::get('/orders',    [OrderHistoryController::class, 'index'])->name('orders');
});

// Seller Dashboard
Route::middleware(['auth', 'role:seller'])->prefix('seller')->group(function () {
    Route::get('/dashboard',        [SellerDashboardController::class, 'index'])->name('seller.dashboard');
    Route::get('/menus',            [MenuController::class, 'index'])->name('seller.menus');
    Route::post('/menus',           [MenuController::class, 'store'])->name('seller.menus.store');
    Route::put('/menus/{menu}',     [MenuController::class, 'update'])->name('seller.menus.update');
    Route::patch('/menus/{menu}/toggle', [MenuController::class, 'toggleAvailability'])->name('seller.menus.toggle');
    Route::delete('/menus/{menu}',  [MenuController::class, 'destroy'])->name('seller.menus.destroy');
    Route::get('/orders',           [SellerOrderController::class, 'index'])->name('seller.orders');
    Route::patch('/orders/{order}', [SellerOrderController::class, 'updateStatus'])->name('seller.orders.update');
    Route::get('/reports',          [SellerReportController::class, 'index'])->name('seller.reports');
});

// Admin / Cashier stubs
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard',   fn() => 'Admin Dashboard')->name('admin.dashboard');
    Route::get('/cashier/dashboard', fn() => 'Cashier Dashboard')->name('cashier.dashboard');
});
