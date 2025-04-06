<?php

namespace App\Models\Repositories;

use App\Enums\ActionType;
use App\Models\Post;

class PostRepository
{
    public function getAll()
    {
        return Post::all();
    }

    public function getById($id)
    {
        return Post::find($id);
    }

    public function create($categoryId, array $postData)
    {
        $post = Post::query()->create($postData);
        $post->categories()->attach($categoryId);
    }

    public function update($post, $categoryId, array $postData)
    {
        $post->update($postData);
        $post->categories()->sync([$categoryId]);
    }

    public function delete($post)
    {
        $post->comments()->delete();
        $post->delete();
    }

    public function like($post)
    {
        $post->likes()->create([
            'user_id' => auth()->id(),
            'action_type' => ActionType::LIKE,
        ]);

        $post->update(['likes_count' => $post->likes_count + 1]);
    }

    public function unlike($post)
    {
        $post->likes()
            ->where('user_id', auth()->id())
            ->delete();

        $post->update(['likes_count' => $post->likes_count - 1]);
    }

    public function bookmark($post)
    {
        $post->bookmarks()->create([
            'user_id' => auth()->id(),
            'action_type' => ActionType::BOOKMARK,
        ]);
    }

    public function unbookmark($post)
    {
        $post->bookmarks()
            ->where('user_id', auth()->id())
            ->delete();
    }
}

