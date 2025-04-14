<?php

namespace App\Services;

use App\Models\Repositories\PostRepository;
use App\Notifications\PostPublishedTelegramNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PostService
{
    protected PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAll(int $page, int $perPage): LengthAwarePaginator
    {
        return $this->postRepository->getAll($page, $perPage);
    }

    public function getById($id)
    {
        return $this->postRepository->getById($id);
    }

    public function show($post)
    {
        return $this->postRepository->show($post);
    }

    public function store($categoryId, array $postData): null
    {
        $postData['author_id'] = Auth::id();
        $this->postRepository->create($categoryId, $postData);

        return null;
    }

    public function update($post, $categoryId, array $postData): null
    {
        $this->postRepository->update($post, $categoryId, $postData);
        $post->author->notify(new PostPublishedTelegramNotification());

        return null;
    }

    public function destroy($post): void
    {
        $this->postRepository->delete($post);
    }

    public function like($post): void
    {
        $this->postRepository->like($post);
    }

    public function unlike($post): void
    {
        $this->postRepository->unlike($post);
    }

    public function bookmark($post): void
    {
        $this->postRepository->bookmark($post);
    }

    public function unbookmark($post): void
    {
        $this->postRepository->unbookmark($post);
    }
}

