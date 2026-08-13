<?php

use App\Http\Controllers\Api\BootstrapController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/bootstrap', BootstrapController::class);

    Route::post('/auth/login', fn () => response()->json(['ok' => false, 'error' => 'Pendiente implementar login Laravel Sanctum.'], 501));
    Route::post('/auth/register', fn () => response()->json(['ok' => false, 'error' => 'Pendiente implementar registro con TenantCapacityService.'], 501));

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', fn () => request()->user());
        Route::get('/chats', fn () => response()->json(['ok' => false, 'error' => 'Pendiente migrar chats.'], 501));
        Route::post('/messages', fn () => response()->json(['ok' => false, 'error' => 'Pendiente migrar mensajes.'], 501));
    });
});

