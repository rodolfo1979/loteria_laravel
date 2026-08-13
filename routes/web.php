<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->json([
    'ok' => true,
    'app' => 'LotoX Business API',
    'version' => 'laravel-13',
]));

