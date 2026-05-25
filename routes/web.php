<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Member Controllers
use App\Http\Controllers\Member\DashboardController as MemberDashboard;
use App\Http\Controllers\Member\EquipmentController as MemberEquipment;
use App\Http\Controllers\Member\HistoryController as MemberHistory;
use App\Http\Controllers\Member\SettingsController as MemberSettings;
use App\Http\Controllers\Member\ReturnController as MemberReturn;
use App\Http\Controllers\Member\ContactController as MemberContact;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\EquipmentController as AdminEquipment;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowing;
use App\Http\Controllers\Admin\HistoryController as AdminHistory;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalytics;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\ProfileController as AdminProfile;
use App\Http\Controllers\Admin\SettingsController as AdminSettings;
use App\Http\Controllers\Admin\ReturnController as AdminReturn;
use App\Http\Controllers\Admin\NotificationController as AdminNotification;


// ============================================================
// Public – redirect root to login
// ============================================================
Route::get('/', function () {
    return redirect()->route('login');
});


// ── Auth ─────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);

    // Forgot Password
    Route::post('/forgot-password',          [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}',    [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',           [AuthController::class, 'resetPassword'])->name('password.update');

    // Google OAuth
    Route::get('/auth/google',          [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ============================================================
// Member Routes
// ============================================================
Route::middleware(['auth'])->prefix('member')->name('member.')->group(function () {

    // Dashboard
    Route::get('/dashboard',     [MemberDashboard::class, 'index'])->name('dashboard');
    Route::get('/notifications', [MemberDashboard::class, 'notifications'])->name('notifications');
    Route::get('/gear-guides',   [MemberDashboard::class, 'gearGuides'])->name('gearGuides');
    Route::get('/active-gear',   [MemberDashboard::class, 'activeGear'])->name('activeGear');

    Route::post('/notifications/mark-read', [MemberDashboard::class, 'markNotificationsRead'])->name('notifications.markRead');

    // Equipment & Borrow
    Route::get('/equipment',                     [MemberEquipment::class, 'index'])->name('equipment.index');
    Route::get('/equipment/{equipment}/borrow',  [MemberEquipment::class, 'showBorrowForm'])->name('equipment.borrow');
    Route::post('/equipment/{equipment}/borrow', [MemberEquipment::class, 'submitBorrow']);

    // History
    Route::get('/history', [MemberHistory::class, 'index'])->name('history.index');

    // Profile
    Route::get('/profile', [MemberDashboard::class, 'profile'])->name('profile');
    Route::put('/profile', [MemberDashboard::class, 'updateProfile'])->name('profile.update');

    // Contact Admin
    Route::post('/contact', [MemberContact::class, 'send'])->name('contact.send');

    // Settings
    Route::get('/settings/lang/{lang}', [MemberSettings::class, 'setLanguage'])->name('settings.lang');
    Route::post('/settings/theme',      [MemberSettings::class, 'setTheme'])->name('settings.theme');

    // Returns
    Route::prefix('returns')->name('returns.')->group(function () {
        Route::get('/',                   [MemberReturn::class, 'index'])->name('index');
        Route::get('/create/{borrowing}', [MemberReturn::class, 'create'])->name('create');
        Route::post('/store/{borrowing}', [MemberReturn::class, 'store'])->name('store');
        Route::get('/{return}',           [MemberReturn::class, 'show'])->name('show');
    });
});


// ============================================================
// Admin Routes
// ============================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Equipment CRUD
    Route::get('/equipment',                [AdminEquipment::class, 'index'])->name('equipment.index');
    Route::post('/equipment',               [AdminEquipment::class, 'store'])->name('equipment.store');
    Route::put('/equipment/{equipment}',    [AdminEquipment::class, 'update'])->name('equipment.update');
    Route::delete('/equipment/{equipment}', [AdminEquipment::class, 'destroy'])->name('equipment.destroy');

    // Borrowing Requests
    Route::get('/borrowing',                          [AdminBorrowing::class, 'index'])->name('borrowing.index');
    Route::get('/borrowing/{borrowing}',              [AdminBorrowing::class, 'show'])->name('borrowing.show');
    Route::post('/borrowing/{borrowing}/approve',     [AdminBorrowing::class, 'approve'])->name('borrowing.approve');
    Route::post('/borrowing/{borrowing}/reject',      [AdminBorrowing::class, 'reject'])->name('borrowing.reject');
    Route::post('/borrowing/{borrowing}/return',      [AdminBorrowing::class, 'markReturned'])->name('borrowing.return');

    // History & Analytics
    Route::get('/history',        [AdminHistory::class, 'index'])->name('history.index');
    Route::get('/history/export', [AdminHistory::class, 'exportCsv'])->name('history.export');
    Route::get('/analytics',      [AdminAnalytics::class, 'index'])->name('analytics.index');

    // Users
    Route::get('/users',                       [AdminUser::class, 'index'])->name('users.index');
    Route::get('/users/{user}',                [AdminUser::class, 'show'])->name('users.show');
    Route::put('/users/{user}',                [AdminUser::class, 'update'])->name('users.update');
    Route::put('/users/{user}/reset-password', [AdminUser::class, 'resetPassword'])->name('users.resetPassword');
    Route::delete('/users/{user}',             [AdminUser::class, 'destroy'])->name('users.destroy');

    // Profile
    Route::get('/profile',          [AdminProfile::class, 'index'])->name('profile');
    Route::put('/profile',          [AdminProfile::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfile::class, 'updatePassword'])->name('profile.password');

    // Settings
    Route::get('/settings',             [AdminSettings::class, 'index'])->name('settings');
    Route::post('/settings',            [AdminSettings::class, 'update'])->name('settings.update');
    Route::post('/settings/reset',      [AdminSettings::class, 'reset'])->name('settings.reset');
    Route::get('/settings/lang/{lang}', [AdminSettings::class, 'setLanguage'])->name('settings.lang');

    // Returns management
    Route::prefix('returns')->name('returns.')->group(function () {
        Route::get('/',               [AdminReturn::class, 'index'])->name('index');
        Route::get('/{id}',           [AdminReturn::class, 'show'])->name('show');
        Route::patch('/{id}/confirm', [AdminReturn::class, 'confirm'])->name('confirm');
        Route::patch('/{id}/reject',  [AdminReturn::class, 'reject'])->name('reject');
    });

    // ── Notifications ──────────────────────────────────────────
    Route::prefix('notifications')->name('notifications.')->group(function () {

        // HALAMAN PENUH — harus di atas route /{id} agar tidak bentrok
        Route::get('/page',           [AdminNotification::class, 'page'])->name('page');

        // JSON API (badge polling)
        Route::get('/',               [AdminNotification::class, 'index'])->name('index');
        Route::get('/unread-count',   [AdminNotification::class, 'unreadCount'])->name('unreadCount');

        // Detail satu notifikasi (JSON, dipakai modal di halaman notifikasi)
        Route::get('/{id}',           [AdminNotification::class, 'show'])->name('show');

        // Actions
        Route::post('/mark-all-read', [AdminNotification::class, 'markAllRead'])->name('markAllRead');
        Route::patch('/{id}/read',    [AdminNotification::class, 'markRead'])->name('markRead');
        Route::delete('/clear-read',  [AdminNotification::class, 'clearRead'])->name('clearRead');
        Route::delete('/{id}',        [AdminNotification::class, 'destroy'])->name('destroy');
    });
});