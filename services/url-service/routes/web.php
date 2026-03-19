<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/shorten', [UrlController::class, 'shorten']);
Route::get('/{code}', [UrlController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9]+');

Route::get('api/stats', function () {
    return \App\Models\Url::select('short_code', 'clicks')->get();
});