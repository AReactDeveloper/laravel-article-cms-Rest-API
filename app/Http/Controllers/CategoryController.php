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
            $categories = Category::withCount('articles')->get();
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
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255'
            ]);

            $newCategory = new Category();
            if ($newCategory === null) {
                return response()->json(['error' => 'An unexpected error occurred. when creating new category.'], 500);
            }

            $newCategory->title = $validated['title'];
            if (!$newCategory->save()) {
                return response()->json(['error' => 'An unexpected error occurred. when creating new category.'], 500);
            }

            return response()->json(['message' => 'Category created successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. when creating new category.'], 500);
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
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['message' => 'Category was deleted successfully.'], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Category was not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. when deleting this category.'], 500);
        }
    }
}
