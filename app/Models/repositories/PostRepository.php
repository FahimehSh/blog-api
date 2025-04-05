<?php

namespace App\Repositories;

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

    public function create(array $postData)
    {
        return Post::query()->create($postData);
    }

    public function update($post, array $postData)
    {
        $post->update($postData);
        return $post;
    }

    public function delete($id)
    {
        $post = Post::query()->findOrFail($id);
        $post->comments()->delete();
        $post->delete();
    }
}

