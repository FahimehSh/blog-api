<?php
declare(strict_types=1);

namespace App\Models\Repositories;

use App\Enums\ActionType;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class PostRepository
{
    public function getAll(): Collection
    {
        return Post::all();
    }

    public function getPublishedPosts(): array
    {
        return Post::query()->where('status', 'published')->get()->toArray();
    }

    public function getById($id)
    {
        return Post::query()->find($id);
    }

    public function create($categoryId, array $postData): void
    {
        $post = Post::query()->create($postData);
        $post->categories()->attach($categoryId);
    }

    public function update($post, $categoryId, array $postData): void
    {
        $post->update($postData);
        $post->categories()->sync([$categoryId]);
    }

    public function delete($post): void
    {
        $post->comments()->delete();
        $post->delete();
    }

    public function like($post): void
    {
        $post->likes()->create([
            'user_id' => auth()->id(),
            'action_type' => ActionType::LIKE,
        ]);

        $post->update(['likes_count' => $post->likes_count + 1]);
    }

    public function unlike($post): void
    {
        $post->likes()
            ->where('user_id', auth()->id())
            ->delete();

        $post->update(['likes_count' => $post->likes_count - 1]);
    }

    public function bookmark($post): void
    {
        $post->bookmarks()->create([
            'user_id' => auth()->id(),
            'action_type' => ActionType::BOOKMARK,
        ]);
    }

    public function unbookmark($post): void
    {
        $post->bookmarks()
            ->where('user_id', auth()->id())
            ->delete();
    }
}

