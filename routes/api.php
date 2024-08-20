<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SiteInfoController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;





Route::Post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(
    function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::put('user', [AuthController::class, 'updateUser']);
        Route::get('user', [AuthController::class, 'getAuthUser']);

        Route::apiResource('articles', ArticleController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('tags', TagController::class);
        Route::apiResource('pages', PageController::class);
        Route::apiResource('settings', SiteInfoController::class);
        Route::apiResource('file', AttachmentsController::class);
    }
);
