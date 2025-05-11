<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'body' => 'required|string',
            'author' => 'nullable|string|max:255',
        ]);
    
        $comment = Comment::create($data);
        
        return response()->json([
            'message' => 'Comment created successfully.',
            'data' => $comment
        ],201);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'body' => 'required|string',
            'author' => 'nullable|string|max:255',
        ]);

        $comment->update($data);

        return response()->json([
            'message' => 'Comment updated successfully.',
            'data' => $comment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * comments will be fetched from posts
     */
    public function destroy(Comment $comment)
    {
        //
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.'
        ], 204);
    }

}
