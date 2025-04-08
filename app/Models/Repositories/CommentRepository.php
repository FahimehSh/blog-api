<?php
declare(strict_types=1);

namespace App\Models\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository
{
    public function getAll(): Collection
    {
        return Comment::all();
    }

    public function getPublishedComments(): array
    {
        return Comment::query()->where('is_published', true)->get()->toArray();
    }

    public function getById($id)
    {
        return Comment::query()->find($id);
    }

    public function create(array $commentData)
    {
        return Comment::query()->create($commentData);
    }

    public function update($comment, array $commentData)
    {
        $comment->update($commentData);
        return $comment;
    }

    public function delete($comment): void
    {
        $comment->delete();
    }

    public function like($comment): void
    {
        $comment->likes()->create([
            'user_id' => auth()->id(),
        ]);

        $comment->update(['likes_count' => $comment->likes_count + 1]);
    }

    public function unlike($comment): void
    {
        $comment->likes()
            ->where('user_id', auth()->id())
            ->delete();

        $comment->update(['likes_count' => $comment->likes_count - 1]);
    }
}
