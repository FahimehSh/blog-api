<?php
declare(strict_types=1);

namespace App\Models\Repositories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

class PostRepository
{
    public function getAll(int $page, int $perPage): LengthAwarePaginator
    {
        return Cache::remember("posts.index.page.{$page}.perPage.{$perPage}", now()->addHour(), function () use ($perPage) {
            return Post::paginate($perPage);
        });
    }

    public function getPublishedPosts(): array
    {
        return Post::query()->where('status', 'published')->get()->toArray();
    }

    public function getById($id)
    {
        return Post::query()->find($id);
    }

    public function show($post)
    {
        if (!Cookie::get('post_viewed_' . $post->id)) {
            $post->increment('views_count');
        }

        return $post;
    }

    public function create($categoryId, array $postData): void
    {
        $post = Post::query()->create($postData);
        Cache::forget('posts.index');
        $post->categories()->attach($categoryId);
        $category = Category::query()->find($categoryId);
        $category->update(['posts_count' => $category->posts_count + 1]);
    }

    public function update($post, $categoryId, array $postData): void
    {
        $post->update($postData);
        Cache::forget('posts.index');
        $post->categories()->syncWithoutDetaching([$categoryId]);
        $category = Category::query()->find($categoryId);
        $category->update(['posts_count' => $category->posts_count + 1]);
    }

    public function delete($post): void
    {
        $post->delete();
    }

    public function like($post): void
    {
        $post->likes()->create([
            'user_id' => auth()->id(),
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
        ]);
    }

    public function unbookmark($post): void
    {
        $post->bookmarks()
            ->where('user_id', auth()->id())
            ->delete();
    }
}

