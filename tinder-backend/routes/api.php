<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\SwipeController;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

Route::prefix('v1')->group(function () {

    // =====================
    // Authentication Routes
    // =====================
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        // Protected auth routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
        });
    });

    // =====================
    // Protected API Routes
    // =====================
    Route::middleware('auth:sanctum')->group(function () {

        // People Routes
        Route::prefix('people')->group(function () {
            Route::get('/', [PersonController::class, 'index']);           // List recommended people
            Route::get('/liked', [PersonController::class, 'liked']);       // Get liked people
            Route::get('/disliked', [PersonController::class, 'disliked']); // Get disliked people
            Route::get('/matches', [PersonController::class, 'matches']);   // Get matches
            Route::get('/{person}', [PersonController::class, 'show']);     // Get specific person
        });

        // Swipe Routes
        Route::prefix('swipe')->group(function () {
            Route::post('/like', [SwipeController::class, 'like']);         // Like a person
            Route::post('/dislike', [SwipeController::class, 'dislike']);   // Dislike a person
            Route::get('/stats', [SwipeController::class, 'stats']);        // Get swipe statistics
            Route::delete('/{swipe}', [SwipeController::class, 'undo']);    // Undo a swipe
        });
    });

    Route::prefix('admin')->middleware('admin')->group(function () {
            // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        
        // User Management
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/users/{id}', [AdminController::class, 'userDetail']);
        Route::put('/users/{id}/role', [AdminController::class, 'updateRole']);
        Route::put('/users/{id}/block', [AdminController::class, 'blockUser']);
        
        // Popular Users
        Route::get('/popular-users', [AdminController::class, 'popularUsers']);
        
        // Email Notifications
        Route::get('/email-logs', [AdminController::class, 'emailLogs']);
        Route::post('/send-popular-notification', [AdminController::class, 'sendPopularNotification']);
    });
});



// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});
