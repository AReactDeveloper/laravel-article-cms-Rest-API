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
            $siteInfo = siteInfo::first();
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $setting
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $setting)
    {
        $request->validate([
            'siteLogoOptions' => 'string|in:logo,logo_title,logo_title_description,title_description',
        ]);
        try {
            $siteInfo = SiteInfo::first();
            if ($siteInfo === null) {
                return response()->json(['error' => 'Site information not found'], 404);
            }

            $input = $request->all();
            $siteInfo->siteLogoOptions = $input['siteLogoOptions'];
            $siteInfo->update($input);
            return response()->json('site info updated succufuly ' . $siteInfo, 200);
        } catch (QueryException $e) {
            report($e);
            return response()->json(['error' => $e . 'Database error.'], 500);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['error' => $e . 'An unexpected error occurred.'], 500);
        }
    }
}
