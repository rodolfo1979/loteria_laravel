<?php

use App\Http\Controllers\Api\BootstrapController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\AdminAnnouncementController;
use App\Http\Controllers\Api\AdminLibraryController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\SuperAdminTenantController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/bootstrap', BootstrapController::class);
    Route::post('/super-admin/auth/login', [AuthController::class, 'superAdminLogin']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/super-admin/tenants', [SuperAdminTenantController::class, 'index']);
        Route::post('/super-admin/tenants', [SuperAdminTenantController::class, 'store']);
        Route::patch('/super-admin/tenants/{tenant}', [SuperAdminTenantController::class, 'update']);
    });

    Route::middleware('lotox.tenant')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/register', [AuthController::class, 'register']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', fn () => response()->json(['ok' => true, 'profile' => request()->user()]));
            Route::get('/chats', [ChatController::class, 'index']);
            Route::post('/chats', [ChatController::class, 'store']);
            Route::post('/messages', [MessageController::class, 'store']);

            Route::get('/admin/users', [AdminUserController::class, 'index']);
            Route::post('/admin/users', [AdminUserController::class, 'store']);
            Route::patch('/admin/users/{user}', [AdminUserController::class, 'update']);
            Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy']);
            Route::get('/admin/announcements', [AdminAnnouncementController::class, 'index']);
            Route::post('/admin/announcements', [AdminAnnouncementController::class, 'store']);
            Route::get('/admin/library', [AdminLibraryController::class, 'index']);
            Route::post('/admin/library/quick-replies', [AdminLibraryController::class, 'storeQuickReply']);
            Route::post('/admin/library/media', [AdminLibraryController::class, 'storeMedia']);
        });
    });
});
