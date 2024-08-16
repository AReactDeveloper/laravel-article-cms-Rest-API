<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tags = Tag::all();
        return response()->json($tags);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $tag = Tag::create($validated);
        return response()->json(['message' => 'tag was created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Check if the tag exists
        $tag = Tag::find($id);
        if (!$tag) {
            return response()->json(['error' => 'Tag not found'], 404);
        }

        // Return the tag
        try {
            return response()->json($tag);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        // Check if the tag exists
        if (!$tag) {
            return response()->json(['error' => 'Tag not found'], 404);
        }

        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Update the tag
        $tag->update([
            'title' => $request->title,
        ]);

        return response()->json(['message' => 'Tag was updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        // Check if the tag exists
        if ($tag === null) {
            return response()->json(['error' => 'Tag not found'], 404);
        }

        try {
            // Delete the tag
            $tag->delete();
            return response()->json(['message' => 'Tag was deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
