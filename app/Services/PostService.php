<?php

namespace App\Services;

use App\Repositories\PostRepository;
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

    public function store(array $postData)
    {
        $postData['author_id'] = Auth::id();
        return $this->postRepository->create($postData);
    }

    public function update($post, array $postData)
    {
        return $this->postRepository->update($post, $postData);
    }

    public function destroy($id)
    {
        $this->postRepository->delete($id);
    }
}

