<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/route-clear', function() {
    Artisan::call('route:clear');
    Artisan::call('route:cache');
    return 'Route cache cleared';
});

Route::get('/config-cache', function() {
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    return 'Clear Config cleared';
});

Route::get('/view-clear', function() {
    Artisan::call('view:clear');
    Artisan::call('view:cache');
    return 'Views cleared';
});