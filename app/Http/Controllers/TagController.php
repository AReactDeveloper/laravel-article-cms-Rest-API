<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tags = Cache::remember('tags',3600,function(){
            return Tag::withCount('articles')->with('articles')->orderBy('created_at', 'desc')->get();
        });
        return response()->json($tags);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $tag = Tag::create($validated);

        //clear cache
        Cache::forget('tags');

        return response()->json(['message' => 'tag was created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($title)
    {
        $tag = Cache::remember('tag_'.$title,3600,function () use($title){
            return Tag::with('articles')->where('title', $title)->first();
        });

        if (!$tag) {
            return response()->json(['error' => 'Tag not found'], 404);
        }

        try {
            return response()->json($tag);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. when fetching categories.'], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $title = $tag->title;
        // Validate the incoming request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $validated['title'] = strtolower($validated['title']);

        // Update the tag
        $tag->update([
            'title' => $validated['title'],
            'description'=> $validated['description']
        ]);


        //clear cache
        Cache::forget('tags');
        Cache::forget('tag_'.$title);

        return response()->json(['message' => 'Tag was updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        try {
            $title = $tag->title;
            // Delete the tag
            $tag->delete();

            //clear cache
            Cache::forget('tags');
            Cache::forget('tag_'.$title);

            return response()->json(['message' => 'Tag was deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
