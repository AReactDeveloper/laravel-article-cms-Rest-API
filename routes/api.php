<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SiteInfoController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;




Route::Post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(
    function () {
        Route::Post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'getAuthUser']);
        Route::apiResource('articles', ArticleController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('tags', TagController::class);
        Route::apiResource('settings', SiteInfoController::class);
    }
);
