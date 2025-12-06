<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return response()->json([
        'name' => 'Tinder Clone API',
        'version' => '1.0.0',
        'documentation' => url('/api/documentation'),
        'health' => url('/api/health'),
    ]);
});
