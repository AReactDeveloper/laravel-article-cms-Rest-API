<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check for maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Require the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap the application...
$app = require_once __DIR__.'/../bootstrap/app.php';

// Handle the incoming request...
$kernel = $app->make(Kernel::class);
$response = $kernel->handle(
    $request = Request::capture()
);

// Send the response to the browser...
$response->send();

// Terminate the request...
$kernel->terminate($request, $response);
