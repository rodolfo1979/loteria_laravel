<?php

use App\Http\Controllers\Api\BootstrapController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\AdminAnnouncementController;
use App\Http\Controllers\Api\AdminLibraryController;
use App\Http\Controllers\Api\AdminLotteryController;
use App\Http\Controllers\Api\AdminLotteryDrawController;
use App\Http\Controllers\Api\AdminLotteryResultController;
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
            Route::get('/announcements', [AnnouncementController::class, 'index']);
            Route::get('/chats', [ChatController::class, 'index']);
            Route::post('/chats', [ChatController::class, 'store']);
            Route::post('/chats/{chat}/clear', [ChatController::class, 'clear']);
            Route::delete('/chats/{chat}', [ChatController::class, 'destroy']);
            Route::post('/messages', [MessageController::class, 'store']);
            Route::post('/messages/mark-read', [MessageController::class, 'markRead']);
            Route::delete('/messages/{message}', [MessageController::class, 'destroy']);

            Route::get('/admin/users', [AdminUserController::class, 'index']);
            Route::post('/admin/users', [AdminUserController::class, 'store']);
            Route::patch('/admin/users/{user}', [AdminUserController::class, 'update']);
            Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy']);
            Route::get('/admin/announcements', [AdminAnnouncementController::class, 'index']);
            Route::post('/admin/announcements', [AdminAnnouncementController::class, 'store']);
            Route::get('/admin/library', [AdminLibraryController::class, 'index']);
            Route::post('/admin/library/quick-replies', [AdminLibraryController::class, 'storeQuickReply']);
            Route::delete('/admin/library/quick-replies/{quickReply}', [AdminLibraryController::class, 'destroyQuickReply']);
            Route::post('/admin/library/media', [AdminLibraryController::class, 'storeMedia']);
            Route::delete('/admin/library/media/{media}', [AdminLibraryController::class, 'destroyMedia']);
            Route::get('/admin/lotteries', [AdminLotteryController::class, 'index']);
            Route::post('/admin/lotteries', [AdminLotteryController::class, 'store']);
            Route::patch('/admin/lotteries/{lottery}', [AdminLotteryController::class, 'update']);
            Route::delete('/admin/lotteries/{lottery}', [AdminLotteryController::class, 'destroy']);
            Route::post('/admin/lotteries/{lottery}/draws', [AdminLotteryDrawController::class, 'store']);
            Route::patch('/admin/lottery-draws/{draw}', [AdminLotteryDrawController::class, 'update']);
            Route::post('/admin/lottery-draws/{draw}/results', [AdminLotteryResultController::class, 'store']);
        });
    });
});

Route::prefix('v1/lotto')->group(function () {
    Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('lotto.login');
    Route::get('/auth/session_check', [\App\Http\Controllers\AuthController::class, 'sessionCheck']);
    Route::post('/personas/cliente_store', [\App\Http\Controllers\PersonaController::class, 'clienteStore']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/ventas', [\App\Http\Controllers\VentaController::class, 'index']);
        Route::get('/ventas/filters', [\App\Http\Controllers\VentaController::class, 'filters']);
        Route::get('/ventas/print', [\App\Http\Controllers\VentaController::class, 'print']);
        Route::get('/ventas/create', [\App\Http\Controllers\VentaController::class, 'create']);
        Route::post('/ventas/save', [\App\Http\Controllers\VentaController::class, 'save']);
        Route::get('/ventas/edit', [\App\Http\Controllers\VentaController::class, 'edit']);

        Route::get('/agencias', [\App\Http\Controllers\AgenciaController::class, 'index']);
        Route::get('/agencias/create', [\App\Http\Controllers\AgenciaController::class, 'create']);
        Route::post('/agencias/save', [\App\Http\Controllers\AgenciaController::class, 'save']);
        Route::get('/agencias/edit', [\App\Http\Controllers\AgenciaController::class, 'edit']);

        Route::get('/roles_paginas_acciones', [\App\Http\Controllers\RolPaginaAccionController::class, 'index']);
        Route::get('/roles_paginas_acciones/create', [\App\Http\Controllers\RolPaginaAccionController::class, 'create']);
        Route::post('/roles_paginas_acciones/save', [\App\Http\Controllers\RolPaginaAccionController::class, 'save']);

        Route::get('/permisos/menu', [\App\Http\Controllers\PermisoController::class, 'menu']);
        Route::get('/permisos/check', [\App\Http\Controllers\PermisoController::class, 'check']);
        Route::get('/permisos/childrens', [\App\Http\Controllers\PermisoController::class, 'getChildrens']);

        Route::post('/auth/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

        Route::get('/personas', [\App\Http\Controllers\PersonaController::class, 'index']);
        Route::get('/personas/info', [\App\Http\Controllers\PersonaController::class, 'info']);
        Route::get('/personas/create', [\App\Http\Controllers\PersonaController::class, 'create']);
        Route::post('/personas/save', [\App\Http\Controllers\PersonaController::class, 'save']);
        Route::get('/personas/edit', [\App\Http\Controllers\PersonaController::class, 'edit']);

        Route::get('/usuarios/accesos', [\App\Http\Controllers\UsuarioController::class, 'accesos']);
        Route::post('/usuarios/save', [\App\Http\Controllers\UsuarioController::class, 'save']);

        Route::get('/loterias', [\App\Http\Controllers\LoteriaController::class, 'index']);
        Route::post('/loterias/save', [\App\Http\Controllers\LoteriaController::class, 'save']);
        Route::get('/loterias/edit', [\App\Http\Controllers\LoteriaController::class, 'edit']);

        Route::get('/juegos', [\App\Http\Controllers\JuegoController::class, 'index']);
        Route::get('/juegos/filters', [\App\Http\Controllers\JuegoController::class, 'filters']);
        Route::get('/juegos/create', [\App\Http\Controllers\JuegoController::class, 'create']);
        Route::post('/juegos/save', [\App\Http\Controllers\JuegoController::class, 'save']);
        Route::get('/juegos/edit', [\App\Http\Controllers\JuegoController::class, 'edit']);

        Route::get('/reportes/juegos/proximos_sorteos', [\App\Http\Controllers\ReporteJuegoController::class, 'proximosSorteos']);
    });
});
