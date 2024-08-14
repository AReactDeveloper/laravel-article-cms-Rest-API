<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use \Illuminate\Database\Eloquent\ModelNotFoundException;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Eager load the tags relationship for all articles
            $articles = Article::query()->with('tags')->get();

            return response()->json($articles, 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An error occurred while retrieving articles.'], 500);
        }
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        try {
            // Validate the incoming request
            $request->validate([
                'title' => 'required|string|min:3|max:255',
                'content' => 'required|string|min:3',
                'imgUrl' => 'required',
                'category_id' => 'string'
            ]);

            // Create a new Article instance
            $article = new Article();
            $article->title = $request->title;
            $article->content = $request->content;
            $article->imgUrl = $request->imgUrl;
            $article->category_id = $request->category_id;

            // Attempt to save the article
            if (!$article->save()) {
                return response()->json(['error' => 'An error occurred while saving the article.'], 500);
            } else {
                return response()->json(['message' => 'Article was created succufuly.'], 200);
            }


            return response()->json($article, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An error occurred while creating the article.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article): JsonResponse
    {
        try {
            // Find the article with the specified ID, and eager load the tags relationship
            $article = Article::with('tags')->findOrFail($article->id);
            $category = Category::findOrFail($article->category_id);
            // Return the article with its tags as a JSON response
            return response()->json($category, 200);
        } catch (ModelNotFoundException $e) {
            // If the article was not found, return a 404 Not Found response
            return response()->json(['error' => 'Article not found'], 404);
        } catch (\Throwable $e) {
            // If any other error occurs, return a 500 Internal Server Error response
            return response()->json(['error' => 'Failed to retrieve article'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        try {
            if (!$article) {
                return response()->json('article was not found');
            }

            // Validate the incoming request
            $request->validate([
                'title' => 'required|string|min:3|max:255',
                'content' => 'required|string|min:3',
                'imgUrl' => 'required',
            ]);

            $article->title = $request->title;
            $article->content = $request->content;
            $article->imgUrl = $request->imgUrl;
            $article->save();

            return response()->json('done');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article): JsonResponse
    {
        try {
            // Delete the article
            $article->delete();

            // Return a 204 No Content response
            return response()->json('deleted', 204);
        } catch (\Throwable $e) {
            // If any error occurs, return a 500 Internal Server Error response
            return response()->json(['error' => 'Failed to delete article'], 500);
        }
    }
}
