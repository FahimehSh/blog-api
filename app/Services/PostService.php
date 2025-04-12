<?php

namespace App\Services;

use App\Jobs\SendTelegramNotification;
use App\Models\Repositories\PostRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class PostService
{
    protected PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAll(): Collection
    {
        return $this->postRepository->getAll();
    }

    public function getById($id)
    {
        return $this->postRepository->getById($id);
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
        $telegramNotificationService = new TelegramNotificationService();
        SendTelegramNotification::dispatch($telegramNotificationService, $post)
            ->onQueue('notification')
            ->delay(now()->addMinute());

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

