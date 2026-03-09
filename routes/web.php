<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\OrderHistoryController;
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

// Admin / Seller / Cashier stubs
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard',   fn() => 'Admin Dashboard')->name('admin.dashboard');
    Route::get('/seller/dashboard',  fn() => 'Seller Dashboard')->name('seller.dashboard');
    Route::get('/cashier/dashboard', fn() => 'Cashier Dashboard')->name('cashier.dashboard');
});
