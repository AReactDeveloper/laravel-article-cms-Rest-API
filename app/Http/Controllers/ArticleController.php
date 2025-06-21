<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    // Cache duration in seconds
    protected $cacheDuration = 3600;

    /**
     * List articles, optionally drafts or published.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $isDraft = $request->query('isDraft');
            $cacheKey = 'articles_list';

            $articles = Cache::remember($cacheKey, $this->cacheDuration, function () use ($isDraft) {
                $query = Article::orderBy('created_at', 'desc')->with(['category', 'tags', 'comments']);
                if ($isDraft === null) {
                    $query->where('isDraft', 0);
                }
                return $query->get();
            });

            if ($articles->isEmpty()) {
                return response()->json(['error' => 'No articles found'], 404);
            }

            return response()->json($articles, 200);
        } catch (QueryException $e) {
            return response()->json(['error' => "An error occurred while fetching articles: {$e->getMessage()}"], 500);
        }
    }

    /**
     * Create new article and clear cache.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:3',
            'excerpt' => 'nullable|string|min:3',
            'imgUrl' => 'nullable|url',
            'category_id' => 'nullable|integer',
            'isDraft' => 'boolean',
            'tags' => 'nullable|array',
        ]);

        $slug = Str::slug($request->title);

        if (DB::table('articles')->where('slug', $slug)->exists()) {
            return response()->json(['error' => 'Article with this title already exists. Titles must be unique.'], 409);
        }

        $article = new Article();
        $article->title = $request->title;
        $article->slug = $slug;
        $article->content = $request->content;
        $article->excerpt = $request->excerpt ?? null;
        $article->imgUrl = $request->imgUrl ?? null;
        $article->isDraft = $request->isDraft ?? false;
        $article->category_id = $request->category_id ?? 1; // default category

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $slug . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $filename);
            $article->imgUrl = '/storage/images/' . $filename;
        }

        $article->save();

        if ($request->filled('tags')) {
            $tagIds = collect($request->tags)->map(function ($tagTitle) {
                return Tag::firstOrCreate(['title' => $tagTitle])->id;
            })->toArray();

            $article->tags()->sync($tagIds);
        }

        // Clear caches
        Cache::forget('articles_list');

        return response()->json([
            'message' => 'Article created successfully.',
            'article' => $article,
            'link' => url($article->slug),
        ], 201);
    }

    /**
     * Show single article by slug, cache the result.
     */
    public function show(string $slug): JsonResponse
    {
        $fromCache = true;
        $cacheKey = "article_{$slug}";

        $article = Cache::remember($cacheKey, $this->cacheDuration, function () use ($slug, &$fromCache) {
            $fromCache = false;
            return Article::with(['tags:id,title', 'category:id,title', 'comments'])
                ->where('slug', $slug)
                ->first();
        });

        if (!$article) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        return response()
            ->json($article, 200)
            ->header('X-From-Cache', $fromCache ? 'true' : 'false');
    }

    /**
     * Update article and clear cache.
     */
    public function update(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:3',
            'excerpt' => 'nullable|string|min:3',
            'imgUrl' => 'nullable|url',
            'category_id' => 'nullable|integer',
            'isDraft' => 'boolean',
            'tags' => 'nullable|array',
        ]);

        $article = Article::with('tags')->where('slug', $slug)->first();

        if (!$article) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        $newSlug = Str::slug($request->title);

        if (DB::table('articles')->where('slug', $newSlug)->where('slug', '<>', $slug)->exists()) {
            return response()->json(['error' => 'Another article with this title already exists.'], 409);
        }

        $article->title = $request->title;
        $article->slug = $newSlug;
        $article->content = $request->content;
        $article->excerpt = $request->excerpt ?? $article->excerpt;
        $article->isDraft = $request->isDraft ?? $article->isDraft;
        $article->imgUrl = $request->imgUrl ?? $article->imgUrl;
        $article->category_id = $request->category_id ?? $article->category_id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $newSlug . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $filename);
            $article->imgUrl = '/storage/images/' . $filename;
        }

        $article->save();

        if ($request->filled('tags')) {
            $tagIds = collect($request->tags)->map(function ($tagTitle) {
                return Tag::firstOrCreate(['title' => $tagTitle])->id;
            })->toArray();

            $article->tags()->sync($tagIds);
        }

        Cache::forget('articles_list');
        Cache::forget("article_{$slug}");
        Cache::forget("article_{$newSlug}");

        return response()->json(['message' => 'Article updated successfully.', 'article' => $article], 200);
    }

    /**
     * Delete article and clear cache.
     */
    public function destroy(int $id): JsonResponse
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        $slug = $article->slug;
        $article->delete();

        Cache::forget('articles_list');
        Cache::forget("article_{$slug}");

        return response()->json(['message' => 'Article deleted successfully.'], 204);
    }
}
