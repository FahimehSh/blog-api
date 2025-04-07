<?php

namespace App\Services;

use App\Models\Repositories\CommentRepository;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getAll()
    {
        return $this->commentRepository->getAll();
    }

    public function getById($id)
    {
        return $this->commentRepository->getById($id);
    }

    public function store(array $commentData)
    {
        $commentData['author_id'] = Auth::id();
        return $this->commentRepository->create($commentData);
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
