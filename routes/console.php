<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('lotox:health', function () {
    $this->info('LotoX Laravel API ready.');
})->purpose('Check LotoX application health');

