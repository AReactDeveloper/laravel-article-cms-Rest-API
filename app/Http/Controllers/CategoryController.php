<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
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
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        try {
            $category = Category::create($validated);
            return response()->json(['message' => $category + 'Category was created successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::find($id);
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
        if ($category === null) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255'
            ]);

            $category->update($validated);
            return response()->json(['message' => 'Category was updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. when updating this category.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category === null) {
            return response()->json(['error' => 'Category was not found.'], 404);
        } else {
            $category->delete();
            return response()->json(['message' => 'Category was deleted successfully.'], 204);
        }
    }
}
