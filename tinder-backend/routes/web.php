<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// Main Login (redirects based on role)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Dashboard Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    
    // Protected admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/users', [DashboardController::class, 'users'])->name('admin.users');
        Route::get('/users/{id}', [DashboardController::class, 'userDetail'])->name('admin.users.detail');
        Route::post('/users/{id}/block', [DashboardController::class, 'toggleBlock'])->name('admin.users.block');
        Route::post('/users/{id}/role', [DashboardController::class, 'updateRole'])->name('admin.users.role');
        Route::get('/popular-users', [DashboardController::class, 'popularUsers'])->name('admin.popular');
        Route::get('/email-logs', [DashboardController::class, 'emailLogs'])->name('admin.email-logs');
        Route::post('/send-notification', [DashboardController::class, 'sendNotification'])->name('admin.send-notification');
    });
});