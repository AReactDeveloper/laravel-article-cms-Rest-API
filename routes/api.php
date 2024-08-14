<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;




//api
Route::apiResource('articles', ArticleController::class)
    ->only('index', 'show', 'store', 'update', 'destroy');
Route::apiResource('tags', CategoryController::class);
Route::apiResource('categories', TagController::class);

//default get away
//any bad request should return an error json response
//instead of rendered html that might expose sensntive server information
Route::any('/{any}', function () {
    return response()->json(['error' => 'Not found'], 404);
})->where('any', '.*');
