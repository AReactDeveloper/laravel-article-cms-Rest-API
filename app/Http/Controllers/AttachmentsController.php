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

            $attachment = new Attachments;

            $request->validate([
                'file' => 'file|mimes:jpg,jpeg,png,gif|max:2048'
            ]);

            $attachment = new Attachments();

            // Check if an image file was uploaded
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $cleanFilename = 'image-' . time() . '.' . $image->extension(); // Generate a clean filename
                $image->storeAs('public/images/attachments/', $cleanFilename); // Store the image
                $attachment->url = 'storage/images/attachments/' . $cleanFilename; // Set the image URL
            }

            $attachment->save();

            return response()->json($attachment, 200, [], JSON_UNESCAPED_SLASHES); //skipping anoying json slashes
        } catch (\Exception $e) {
            return response()->json(['message' => $e . 'An error occurred while saving the attachment: ' . $e->getMessage()], 500);
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
