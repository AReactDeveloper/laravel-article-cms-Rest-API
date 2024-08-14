<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;


Route::apiResource('articles', ArticleController::class)
    ->only('index', 'show', 'store', 'update', 'destroy');
Route::apiResource('tags', CategoryController::class);
Route::apiResource('categories', TagController::class);
