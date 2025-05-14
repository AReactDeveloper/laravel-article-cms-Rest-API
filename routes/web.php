<?php

use Illuminate\Support\Facades\Route;


Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF token set']);
});

Route::get('/', function () {
    return 'hello world';
});
