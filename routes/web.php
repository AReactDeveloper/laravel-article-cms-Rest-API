<?php

use Illuminate\Support\Facades\Route;

// Custom CSRF route for debugging
Route::get('/api/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF token set']);
});
