<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create([
            'user_id' => auth()->user()->id,
            'property_id' => $request->property_id,
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        // Check if the authenticated user is the owner of the comment
        if ($comment->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'You are not authorized to update this comment'], 403);
        }
        $comment->update([
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Check if the authenticated user is the owner of the comment
        if ($comment->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'You are not authorized to delete this comment'], 403);
        }
        $comment->update(['deleted' => true]);

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
