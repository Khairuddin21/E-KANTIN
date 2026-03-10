<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SellerOrderController;
use App\Http\Controllers\SellerReportController;
use App\Http\Controllers\Seller\SellerWalletController;
use App\Http\Controllers\Admin\AdminWithdrawalController;
use App\Http\Controllers\TopupController;
use Illuminate\Support\Facades\Route;

// Landing
Route::get('/', function () {
    return view('landing');
});

// Midtrans Notification Webhook (no auth, no CSRF)
Route::post('/midtrans/notification', [CheckoutController::class, 'midtransNotification'])
    ->name('midtrans.notification');
Route::post('/midtrans/topup-notification', [TopupController::class, 'notification'])
    ->name('midtrans.topup.notification');

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

    // Menu, Cart & Checkout
    Route::get('/student/menu',      [CartController::class, 'menuPage'])->name('student.menu');
    Route::get('/student/cart',      [CartController::class, 'index'])->name('student.cart');
    Route::post('/student/cart/add', [CartController::class, 'addToCart'])->name('student.cart.add');
    Route::patch('/student/cart/update', [CartController::class, 'updateQuantity'])->name('student.cart.update');
    Route::delete('/student/cart/remove', [CartController::class, 'removeFromCart'])->name('student.cart.remove');
    Route::get('/student/cart/count', [CartController::class, 'count'])->name('student.cart.count');
    Route::get('/student/checkout',  [CheckoutController::class, 'checkout'])->name('student.checkout');
    Route::post('/student/checkout', [CheckoutController::class, 'processCheckout'])->name('student.checkout.process');
    Route::get('/student/order-success/{order}', [CheckoutController::class, 'orderSuccess'])->name('student.order.success');
    Route::post('/student/order/{order}/cancel', [CheckoutController::class, 'cancelOrder'])->name('student.order.cancel');

    // Profile
    Route::get('/student/profile',  [\App\Http\Controllers\ProfileController::class, 'edit'])->name('student.profile');
    Route::put('/student/profile',  [\App\Http\Controllers\ProfileController::class, 'update'])->name('student.profile.update');

    // Top Up
    Route::post('/student/topup', [TopupController::class, 'store'])->name('student.topup.store');
    Route::post('/student/topup/{topup}/confirm', [TopupController::class, 'confirm'])->name('student.topup.confirm');
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
    Route::delete('/orders/{order}/dismiss', [SellerOrderController::class, 'dismiss'])->name('seller.orders.dismiss');
    Route::get('/reports',          [SellerReportController::class, 'index'])->name('seller.reports');
    Route::get('/wallet',           [SellerWalletController::class, 'wallet'])->name('seller.wallet');
    Route::post('/withdraw',        [SellerWalletController::class, 'storeWithdrawal'])->name('seller.withdraw.store');
    Route::get('/withdrawals',      [SellerWalletController::class, 'history'])->name('seller.withdrawals');
});

// Admin / Cashier stubs
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', fn() => 'Admin Dashboard')->name('admin.dashboard');
    Route::get('/withdrawals',                [AdminWithdrawalController::class, 'index'])->name('admin.withdrawals');
    Route::patch('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('admin.withdrawals.approve');
    Route::patch('/withdrawals/{withdrawal}/reject',  [AdminWithdrawalController::class, 'reject'])->name('admin.withdrawals.reject');
    Route::patch('/withdrawals/{withdrawal}/paid',    [AdminWithdrawalController::class, 'markPaid'])->name('admin.withdrawals.paid');
});

Route::middleware('auth')->group(function () {
    Route::get('/cashier/dashboard', fn() => 'Cashier Dashboard')->name('cashier.dashboard');
});
