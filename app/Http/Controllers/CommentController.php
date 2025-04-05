<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index()
    {
        $comments = $this->commentService->getAll();
        return response()->json($comments);
    }

    public function show($id)
    {
        $comment = $this->commentService->getById($id);
        return response()->json($comment);
    }

    public function store(Request $request)
    {
        $commentData = $request->all();
        $comment = $this->commentService->store($commentData);
        return response()->json($comment, 200);
    }

    public function update(int $id, Request $request): \Illuminate\Http\JsonResponse
    {
        $commentData = $request->all();

        $comment = $this->commentService->getById($id);
        if (Auth::id() !== $comment->author_id && !Auth::user()->is_admin) {
            return response()->json(['message' => 'شما مجوز لازم برای به روز رسانی این کامنت را ندارید.'], 403);
        }

        if (Auth::id() == $comment->author_id && $comment->is_published) {
            return response()->json(['message' => 'امکان به روزرسانی این کامنت وجود ندارد.'], 403);
        }

        if (isset($commentData['is_published'])) {
            if (!Auth::user()->is_admin) {
                return response()->json(['message' => 'شما مجوز لازم برای به روز رسانی این پست را ندارید.'], 403);
            } else {
                $commentData['is_published'] = $commentData['is_published'] ? true : false;
                $commentData['published_at'] = $commentData['is_published'] ? now() : null;
            }
        }

        $comment = $this->commentService->update($comment, $commentData);
        return response()->json($comment, 200);
    }

    public function destroy($id)
    {
        if (Auth::user()->is_admin == false) {
            return response()->json(['message' => 'شما دسترسی ندارید!'], 403);
        }

        $this->commentService->destroy($id);
        return response()->json(['message' => 'کامنت مورد نظر حذف شد!'], 200);
    }
}

