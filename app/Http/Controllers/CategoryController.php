<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Cache::remember('categories',3600,function(){
                return Category::withCount('articles')->with('articles')->orderBy('created_at', 'desc')->get();
            });
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. when fetching categories.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $validated['title'] = Str::lower($validated['title']);

        // Create a new Category instance
        $newCategory = Category::create([
            'title' => $validated['title'],
            'description'=> $validated['description']
        ]);

        
        // If the category is created successfully
        if (!$newCategory) {
            return response()->json(['error' => 'An unexpected error occurred while creating the category.'], 500);
        }
        
        //clear cache
        Cache::forget('categories');

        return response()->json(['message' => 'Category created successfully.'], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($title)
    {
        $category = Cache::remember('category_'.$title,3600,function () use($title){
            return Category::with('articles')->where('title', $title)->first();
        });

        if ($category === null) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        try {
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. when fetching categories.'], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $validated['title'] = Str::lower($validated['title']);

        if (!$category->update($validated)) {
            return response()->json(['error' => 'Failed to update category.'], 500);
        }

        $title = $category->title;

        //clear cache
        Cache::forget('categories');
        Cache::forget('category_'.$title);


        return response()->json(['message' => 'Category was updated successfully.'], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            $title = $category->title;
            //clear cache
            Cache::forget('categories');
            Cache::forget('category_'.$title);
            return response()->json(['message' => 'Category was deleted successfully.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Category was not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. when deleting this category.'], 500);
        }
    }
}
