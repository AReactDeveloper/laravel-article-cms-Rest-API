<?php

use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

//Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

Route::get('/', function () {
    phpinfo();
});
