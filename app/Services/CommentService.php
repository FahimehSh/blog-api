<?php

namespace App\Services;

use App\Models\Repositories\CommentRepository;
use App\Models\Repositories\PostRepository;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    protected $commentRepository;
    protected $postRepository;

    public function __construct(CommentRepository $commentRepository, PostRepository $postRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->postRepository = $postRepository;
    }

    public function getAll()
    {
        return $this->commentRepository->getAll();
    }

    public function getById($id)
    {
        return $this->commentRepository->getById($id);
    }

    public function store(int $postId, array $commentData): null
    {
        $this->commentRepository->create($postId, $commentData);
        return null;
    }

    public function update($comment, array $commentData)
    {
        if (isset($commentData['is_published']) && $commentData['is_published']) {
            $commentData['published_at'] = now();
        } else {
            $commentData['published_at'] = null;
        }
        return $this->commentRepository->update($comment, $commentData);
    }

    public function destroy($comment): void
    {
        $this->commentRepository->delete($comment);
    }

    public function like($comment): void
    {
        $this->commentRepository->like($comment);
    }

    public function unlike($comment): void
    {
        $this->commentRepository->unlike($comment);
    }
}
