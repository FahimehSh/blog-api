<?php

namespace App\Models\Repositories;

use App\Enums\ActionType;
use App\Models\Comment;

class CommentRepository
{
    public function getAll()
    {
        return Comment::all();
    }

    public function getById($id)
    {
        return Comment::find($id);
    }

    public function create(array $commentData)
    {
        return Comment::create($commentData);
    }

    public function update($comment, array $commentData)
    {
        $comment->update($commentData);
        return $comment;
    }

    public function delete($comment)
    {
        $comment->delete();
    }

    public function like($comment)
    {
        $comment->likes()->create([
            'user_id' => auth()->id(),
            'action_type' => ActionType::LIKE,
        ]);

        $comment->update(['likes_count' => $comment->likes_count + 1]);
    }

    public function unlike($comment)
    {
        $comment->likes()
            ->where('user_id', auth()->id())
            ->delete();

        $comment->update(['likes_count' => $comment->likes_count - 1]);
    }
}
