<?php

namespace App\Http\Controllers;

use App\Models\SiteInfo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SiteInfoController extends Controller
{

    /**
     * This is the settings page, intended to be edited only by the admin.
     * The functionality is restricted to viewing and updating settings only; no create or delete operations are allowed.
     * These settings should be accessible via the front-end admin area of the application.
     * While additional security measures could be implemented to restrict access to normal users,
     * our application currently has only one user (the admin), so such measures are unnecessary at this time.
     */

    public function index()
    {
        //
        try {
            $siteInfo = siteInfo::all();
            return response()->json($siteInfo, 200);
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Error retrieving site information!');
        }
    }

    public function show($setting)
    {
        try {
            // Get the value of the setting from the database
            $setting = SiteInfo::value($setting);

            // Return the value as a JSON response
            return response()->json($setting, 200);
        } catch (QueryException $e) {
            // If there is an error with the database query, return a 404 error
            return response()->json(['error' => 'Not Found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteInfo $siteInfo) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $setting)
    {
        // Validate incoming request
        $request->validate([
            'value' => ['required'], // Ensure 'value' is provided
        ]);

        // List of valid column names for security
        $validColumns = ['sitefavicon', 'sitelogo', 'sitename', 'sitedescription', 'siteurl', 'siteadminemail', 'sitelanguage', 'sitestatus'];

        // Check if $setting is a valid column name
        if (in_array($setting, $validColumns)) {
            try {
                // Retrieve the model instance (you might need to adjust this query based on your requirements)
                $siteInfo = SiteInfo::first(); // Or use other criteria to retrieve the specific record

                if ($siteInfo) {
                    // Dynamically update the column value
                    $siteInfo->{$setting} = $request->value;
                    $siteInfo->save(); // Save changes to the database

                    // Return the updated model as a JSON response
                    return response()->json($siteInfo->$setting . " - is your now blog title successfully", 200);
                } else {
                    // Handle case where no record was found
                    return response()->json(['error' => 'Not Found'], 404);
                }
            } catch (\Exception $e) {
                // Handle other errors
                return response()->json(['error' => 'Internal Server Error'], 500);
            }
        } else {
            // Handle invalid column names
            return response()->json(['error' => 'Invalid Setting'], 400);
        }
    }
}
