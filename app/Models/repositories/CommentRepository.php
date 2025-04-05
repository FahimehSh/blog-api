<?php

namespace App\Repositories;

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

    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
    }
}
