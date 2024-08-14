<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;


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
                return response()->json(['message' => 'Article was created successfully.'], 200);
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
    public function show($id): JsonResponse
    {
        $article =  Article::find($id);
        if ($article === null) {
            return response()->json(['error' => 'Article not found'], 404);
        }
        return response()->json($article, 200);
    }


    public function update(Request $request, Article $article): JsonResponse
    {
        try {
            if (!$article) {
                return response()->json(['error' => 'Article was not found.'], 200);
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

            return response()->json(['message' => 'Article was updated successfully.'], 200);
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
            return response()->json(['message' => 'Article was deleted successfully.'], 204);
        } catch (\Throwable $e) {
            // If any error occurs, return a 500 Internal Server Error response
            return response()->json(['error' => 'Failed to delete article'], 500);
        }
    }
}
