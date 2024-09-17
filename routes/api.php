<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SiteInfoController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;



// Public routes
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('articles', ArticleController::class)->only(['index', 'show']);

// Public routes for other resources
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('tags', TagController::class)->only(['index', 'show']);
Route::apiResource('pages', PageController::class)->only(['index', 'show']);
Route::apiResource('settings', SiteInfoController::class)->only(['index', 'show']);
Route::apiResource('file', AttachmentsController::class)->only(['index', 'show']);


// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::put('user', [AuthController::class, 'updateUser']);
    Route::get('user', [AuthController::class, 'getAuthUser']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Protected routes for articles and full CRUD for other resources
    Route::apiResource('articles', ArticleController::class)->except(['index', 'show']);
    Route::apiResource('settings', SiteInfoController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('tags', TagController::class)->except(['index', 'show']);
    Route::apiResource('pages', PageController::class)->except(['index', 'show']);
    Route::apiResource('file', AttachmentsController::class)->except(['index', 'show']);
});
