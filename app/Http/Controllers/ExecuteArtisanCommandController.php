<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ExecuteArtisanCommandController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Check for a specific parameter to trigger migrations
        try {
            // Run the migrations
            Artisan::call('migrate', [
                '--force' => true, // Ensure that migrations run in production
            ]);

            return response()->json([
                'message' => 'Migrations run successfully.',
                'output' => Artisan::output(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error running migrations.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json(['message' => 'No action taken.']);
    }
}
