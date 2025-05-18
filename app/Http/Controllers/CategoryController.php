<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::withCount('articles')->with('articles')->get();
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
        ]);

        $validated['title'] = Str::lower($request->title);

        // Create a new Category instance
        $newCategory = Category::create([
            'title' => $validated['title'],
        ]);

        // If the category is created successfully
        if (!$newCategory) {
            return response()->json(['error' => 'An unexpected error occurred while creating the category.'], 500);
        }

        return response()->json(['message' => 'Category created successfully.'], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($title)
    {
        $category = Category::with('articles')->where('title', $title)->first();
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
            'description'=>'string'
        ]);

        if (!$category->update($validated)) {
            return response()->json(['error' => 'Failed to update category.'], 500);
        }

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
            return response()->json(['message' => 'Category was deleted successfully.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Category was not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. when deleting this category.'], 500);
        }
    }
}
