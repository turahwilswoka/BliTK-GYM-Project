<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerChatController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MemberController as AdminMember;
use App\Http\Controllers\Admin\PaymentController as AdminPayment;
use App\Http\Controllers\Admin\ScannerController as AdminScanner;
use App\Http\Controllers\Admin\ChatController as AdminChat;
use Illuminate\Support\Facades\Route;

// ─── Public: Landing Page ────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ─── Auth Routes ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // ─── Lupa Password ───────────────────────────────────────────────────────────
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ─── QR Code: Public Validate (for scanning apps) ────────────────────────────
Route::get('/qr/validate/{token}', [QrCodeController::class, 'validate'])->name('qrcode.validate');
Route::get('/qr/image/{token}', [QrCodeController::class, 'generate'])->name('qrcode.generate');

// ─── Customer Routes ──────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    // Halaman membership (bisa diakses meski belum punya membership)
    Route::get('/membership', [CustomerController::class, 'membership'])->name('membership');
    Route::post('/membership/buy', [CustomerController::class, 'buyMembership'])->name('membership.buy');

    // Halaman status pembayaran (bisa diakses meski belum aktif)
    Route::get('/payment-status', [CustomerController::class, 'paymentStatus'])->name('payment.status');

    // Halaman yang butuh membership aktif
    Route::middleware(\App\Http\Middleware\CheckActiveSubscription::class)->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
        Route::get('/qrcode', [QrCodeController::class, 'show'])->name('qrcode');
    });

    // Chat routes (accessible to all authenticated users)
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::post('/session',  [CustomerChatController::class, 'getOrCreateSession'])->name('session');
        Route::post('/send',     [CustomerChatController::class, 'sendMessage'])->name('send');
        Route::get('/messages',  [CustomerChatController::class, 'getMessages'])->name('messages');
        Route::get('/history',   [CustomerChatController::class, 'getHistory'])->name('history');
    });
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', \App\Http\Middleware\CheckAdminRole::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Members
        Route::get('/members', [AdminMember::class, 'index'])->name('members.index');
        Route::get('/members/{user}', [AdminMember::class, 'show'])->name('members.show');

        // Payments
        Route::get('/payments', [AdminPayment::class, 'index'])->name('payments.index');
        Route::post('/payments/{subscription}/confirm', [AdminPayment::class, 'confirm'])->name('payments.confirm');
        Route::post('/payments/{subscription}/reject', [AdminPayment::class, 'reject'])->name('payments.reject');

        // QR Scanner
        Route::get('/scanner', [AdminScanner::class, 'index'])->name('scanner');
        Route::post('/scanner/scan', [AdminScanner::class, 'scan'])->name('scanner.scan');

        // Attendance Logs
        Route::get('/attendance', [AdminScanner::class, 'attendance'])->name('attendance');

        // Live Chat
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/',                           [AdminChat::class, 'index'])->name('index');
            Route::get('/poll',                       [AdminChat::class, 'listPoll'])->name('poll');
            Route::get('/pending-count',              [AdminChat::class, 'pendingCount'])->name('pending.count');
            Route::post('/{session}/claim',           [AdminChat::class, 'claim'])->name('claim');
            Route::get('/{session}',                  [AdminChat::class, 'show'])->name('show');
            Route::post('/{session}/send',            [AdminChat::class, 'sendMessage'])->name('send');
            Route::get('/{session}/messages',         [AdminChat::class, 'getMessages'])->name('messages');
            Route::post('/{session}/complete',        [AdminChat::class, 'complete'])->name('complete');
        });
    });
