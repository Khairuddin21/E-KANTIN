<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
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

// Protected stubs (replace with real controllers later)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',         fn() => 'Student/Teacher Dashboard')->name('dashboard');
    Route::get('/admin/dashboard',   fn() => 'Admin Dashboard')->name('admin.dashboard');
    Route::get('/seller/dashboard',  fn() => 'Seller Dashboard')->name('seller.dashboard');
    Route::get('/cashier/dashboard', fn() => 'Cashier Dashboard')->name('cashier.dashboard');
});
