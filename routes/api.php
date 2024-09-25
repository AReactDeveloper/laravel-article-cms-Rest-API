<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SiteInfoController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;


Route::get('/db-migrate', function () {
    try {
        // Run the artisan command: migrate:fresh --seed
        Artisan::call('migrate');

        // Get the output of the command
        $output = Artisan::output();

        return response()->json([
            'status' => 'success',
            'message' => 'Database migrated successfully!',
            'output' => $output,
        ]);
    } catch (\Exception $e) {
        // Handle any exceptions and return a meaningful error message
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred during migration.',
            'error' => $e->getMessage(),
        ], 500);
    }
});


Route::get('/test-db', function () {
    try {
        // Attempt to get the database connection
        $pdo = DB::connection()->getPdo();

        // Get database connection details
        $database = DB::connection()->getDatabaseName();
        $host = $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $tables = DB::select('SHOW TABLES');

        return response()->json([
            'status' => 'success',
            'message' => 'Database connection is working!',
            'details' => [
                'database' => $database,
                'host' => $host,
                'driver' => $driver,
                'tables' => $tables
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed: ' . $e->getMessage(),
        ], 500);
    }
});




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
    Route::post('/passwordChange', [AuthController::class, 'changePassword']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Protected routes for articles and full CRUD for other resources
    Route::apiResource('articles', ArticleController::class)->except(['index', 'show']);
    Route::apiResource('settings', SiteInfoController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('tags', TagController::class)->except(['index', 'show']);
    Route::apiResource('pages', PageController::class)->except(['index', 'show']);
    Route::apiResource('file', AttachmentsController::class)->except(['index', 'show']);
});
