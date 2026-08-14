<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json([
    'ok' => true,
    'app' => 'LotoX Business API',
    'version' => 'laravel-13',
]));

Route::get('/{any}', fn () => view('app'))->where('any', '.*');
