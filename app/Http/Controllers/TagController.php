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
        $tags = Tag::withCount('articles')->with('articles')->get();
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
            'title' => 'required|string|max:255',
            'description'=>'string'
        ]);

        $tag = Tag::create($validated);
        return response()->json(['message' => 'tag was created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($title)
    {
        $tag = Tag::with('articles')->where('title', $title)->first();
        if (!$tag) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        try {
            return response()->json($tag);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. when fetching categories.'], 500);
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
        try {
            // Delete the tag
            $tag->delete();
            return response()->json(['message' => 'Tag was deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
