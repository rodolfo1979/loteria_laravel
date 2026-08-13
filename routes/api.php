<?php

use App\Http\Controllers\Api\BootstrapController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/bootstrap', BootstrapController::class);

    Route::middleware('lotox.tenant')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/register', [AuthController::class, 'register']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', fn () => response()->json(['ok' => true, 'profile' => request()->user()]));
            Route::get('/chats', [ChatController::class, 'index']);
            Route::post('/chats', [ChatController::class, 'store']);
            Route::post('/messages', [MessageController::class, 'store']);
        });
    });
});
