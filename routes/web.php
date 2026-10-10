<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()
        ->view('bacara')
        ->header('Cache-Control', 'no-store')
        ->header('X-Frame-Options', 'DENY')
        ->header('X-Content-Type-Options', 'nosniff')
        ->header('Referrer-Policy', 'no-referrer')
        ->header('Permissions-Policy', 'camera=(self), microphone=(), geolocation=()');
});
