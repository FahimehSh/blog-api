<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeCommentRequest;
use App\Http\Requests\updateCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(): JsonResponse
    {
        $comments = $this->commentService->getAll();
        return response()->json($comments);
    }

    public function show(int $id): JsonResponse
    {
        $comment = $this->commentService->getById($id);
        return response()->json($comment);
    }

    public function store(storeCommentRequest $request): JsonResponse
    {
        $commentData = $request->all();
        $this->commentService->store($commentData);

        return response()->json(['message' => 'کامنت شما با موفقیت ذخیره شد.']);
    }

    public function update(int $id, updateCommentRequest $request): JsonResponse
    {
        $commentData = $request->all();

        $comment = $this->commentService->getById($id);
        if (!$comment) {
            return response()->json(['message' => 'کامنت مورد نظر وجود ندارد.'], 404);
        }

        if (Auth::id() !== $comment->author_id && Auth::user()->is_admin === false) {
            return response()->json(['message' => 'شما مجوز لازم برای به روز رسانی این کامنت را ندارید.'], 403);
        }

        if (Auth::id() === $comment->author_id && $comment->is_published) {
            return response()->json(['message' => 'امکان به روزرسانی این کامنت وجود ندارد.'], 403);
        }

        if (isset($commentData['is_published'])) {
            if (!Auth::user()->is_admin) {
                return response()->json(['message' => 'شما مجوز لازم برای به روز رسانی این پست را ندارید.'], 403);
            }

            $commentData['is_published'] = (bool)$commentData['is_published'];
            $commentData['published_at'] = $commentData['is_published'] ? now() : null;

        }

        $this->commentService->update($comment, $commentData);

        return response()->json(['message' => 'کامنت شما با موفقیت به روز رسانی شد.']);
    }

    public function destroy(int $id): JsonResponse
    {
        $comment = $this->commentService->getById($id);
        if (!$comment) {
            return response()->json(['message' => 'کامنت مورد نظر وجود ندارد.'], 404);
        }

        if (!Auth::user()->is_admin) {
            return response()->json(['message' => 'شما دسترسی ندارید!'], 403);
        }

        $this->commentService->destroy($comment);

        return response()->json(['message' => 'کامنت مورد نظر حذف شد!']);
    }

    public function like(int $id): JsonResponse
    {
        $comment = $this->commentService->getById($id);
        if (!$comment) {
            return response()->json(['message' => 'کامنت مورد نظر وجود ندارد.'], 404);
        }

        $this->commentService->like($comment);

        return response()->json();
    }

    public function unlike(int $id): JsonResponse
    {
        $comment = $this->commentService->getById($id);
        if (!$comment) {
            return response()->json(['message' => 'کامنت مورد نظر وجود ندارد.'], 404);
        }

        $this->commentService->unlike($comment);

        return response()->json();
    }
}

