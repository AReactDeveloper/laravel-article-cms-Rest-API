<?php

namespace App\Http\Controllers;

use App\Models\Attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $attachments = Attachments::all();

            if ($attachments === null) {
                return response()->json(['message' => 'No attachments found'], 404);
            }

            return response()->json([
                'success' => true,
                'url' => $attachments,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching attachments'], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048', // Added 'required' for better validation
            ]);

            // Create new attachment instance
            $attachment = new Attachments();

            // Check if an image file was uploaded
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $cleanFilename = 'image-' . time() . '.' . $image->extension(); // Generate a clean filename
                $image->storeAs('public/images/attachments/', $cleanFilename); // Store the image

                // Set the image URL (adjust the path based on your actual storage configuration)
                $attachment->url = 'storage/images/attachments/' . $cleanFilename; 
            }

            // Save the attachment
            $attachment->save();

            // Return success response
            return response()->json($attachment, 200, [], JSON_UNESCAPED_SLASHES); // Avoid slashes in JSON response
        } catch (\Exception $e) {
            // Handle errors and return user-friendly message
            return response()->json(['message' => 'An error occurred while saving the attachment.'], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $attachment = Attachments::find($id);
        if ($attachment === null) {
            return response()->json(['error' => 'Attachment not found'], 404);
        }
        return response()->json($attachment);
    }


    public function destroy($id)
    {
        try {
            // Find the attachment by ID
            $attachment = Attachments::find($id);

            // Check if the attachment exists
            if ($attachment === null) {
                return response()->json(['error' => 'Attachment not found'], 404);
            }

            // Get the file path based on the attachment's URL
            $file_path = $attachment->url ? storage_path('app/public/' . $attachment->url) : null;
            $file_path = str_replace('/storage', '', $attachment->url);

            // Check if the file exists, then delete the file and attachment record
            if ($file_path && File::exists($file_path)) {
                File::delete($file_path);
                $attachment->delete();
                return response()->json(['message' => 'Attachment deleted successfully.'], 200);
            } else {
                return response()->json(['message' => 'File not found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => "An error occurred while deleting the attachment: " . $e->getMessage()], 500);
        }
    }
}
