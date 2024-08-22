<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\SiteInfo;
use App\Models\Tag;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function Laravel\Prompts\error;

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
            //load posts per page from site settings
            //this can be changed from the front end admin dashboard
            // ?? 10 provides default value if post per page is not set
            //$postPerPage = SiteInfo::value('sitePostsPerPage') ?? 10;
            // Eager load the tags relationship for all articles

            $articles = Article::with(['category', 'tags' => function ($query) {
                $query->withcount('articles')->get(); // Get Post count from each category and tag
            }])->get();

            if ($articles === null) {
                return response()->json(['error' => 'No article was found , create one today'], 404);
            }

            return response()->json($articles, 200);
        } catch (QueryException $e) { //custom errror for db query
            return response()->json(['error' => "An error occurred while fetching articles"], 500);
        }
    }


    public function store(Request $request): JsonResponse
    {

        try {
            // Validate the incoming request
            $request->validate([
                'title' => 'required|string|min:3|max:255',
                'content' => 'required|string|min:3',
                'imgUrl' => 'url',
                'category_id' => 'string',
                'tags' => 'array',
                'image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048' //image validation
            ]);

            // Create a new Article instance
            $article = new Article();
            $article->title = $request->title;
            $newSlug = str_replace(' ', '-', $article->title);

            // The 'slug' field must be unique to avoid conflicts when accessing content via URLs.
            // One solution is to dynamically generate a unique slug if the provided slug already exists in the database.
            // Another approach could be to instruct the user to provide a unique slug manually.
            // Due to performance concerns, we are opting to have the user change the title if a conflict occurs,
            // rather than generating a unique slug dynamically.

            if (DB::table('articles')->where('slug', $newSlug)->exists()) {
                return response()->json(['error' => 'Article already exists. titles must be unique'], 409);
            }

            $article->slug = $newSlug;
            $article->content = $request->content;
            $article->imgUrl = $request->imgUrl;
            $article->category_id = $request->category_id;

            // Check if an image file was uploaded
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $cleanFilename = Str::slug($request->title)  . '.' . $image->extension(); // Generate a clean filename
                $image->storeAs('public/images', $cleanFilename); // Store the image
                $article->imgUrl = '/storage/images/' . $cleanFilename; // Set the image URL
            }

            // Attempt to save the article
            if (!$article->save()) {
                return response()->json(['error' => 'An error occurred while saving the article.'], 500);
            } else {
                if (isset($request->tags)) {
                    //making sure the article is save before adding tags to it
                    //to avoid article id not found error
                    $tagIds = [];
                    foreach ($request->tags as $tagName) {
                        // Find the tag by name, or create it if it doesn't exist
                        $tag = Tag::firstOrCreate(['title' => $tagName]);
                        $tagIds[] = $tag->id;
                    }
                    // Sync the tags with the article
                    $article->tags()->sync($tagIds);
                    return response()->json(['message' => 'Article was created successfully.'], 200);
                }
            }

            return response()->json($article, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $article = Article::with('tags')->find($id);
        if ($article === null) {
            return response()->json(['error' => 'Article not found'], 404);
        }
        return response()->json($article, 200);
    }


    public function update(Request $request, $id): JsonResponse
    {
        $article =  Article::find($id);
        if ($article === null) {
            return response()->json(['error' => 'Article was not found.'], 404);
        } else {
            // Validate the incoming request
            $request->validate([
                'title' => 'required|string|min:3|max:255',
                'content' => 'required|string|min:3',
                'imgUrl' => 'string',
            ]);

            $article->title = $request->title;
            $article->content = $request->content;
            $article->imgUrl = $request->imgUrl;
            $article->save();
            return response()->json(['message' => 'Article was updated successfully.'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $article = Article::find($id);
        if ($article === null) {
            return response()->json(['error' => 'Article was not found.'], 404);
        } else {
            // Delete the article
            $article->delete();
            // Return a 204 No Content response
            return response()->json(['message' => 'Article was deleted successfully.'], 204);
        }
    }
}
