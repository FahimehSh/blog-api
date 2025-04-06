<?php

namespace App\Services;

use App\Models\Repositories\PostRepository;
use Illuminate\Support\Facades\Auth;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAll()
    {
        return $this->postRepository->getAll();
    }

    public function getById($id)
    {
        return $this->postRepository->getById($id);
    }

    public function store($categoryId, array $postData)
    {
        $postData['author_id'] = Auth::id();
        return $this->postRepository->create($categoryId, $postData);
    }

    public function update($post, $categoryId, array $postData)
    {
        return $this->postRepository->update($post, $categoryId, $postData);
    }

    public function destroy($post)
    {
        $this->postRepository->delete($post);
    }

    public function like($post)
    {
        $this->postRepository->like($post);
    }

    public function unlike($post)
    {
        $this->postRepository->unlike($post);
    }

    public function bookmark($post)
    {
        $this->postRepository->bookmark($post);
    }

    public function unbookmark($post)
    {
        $this->postRepository->unbookmark($post);
    }
}

