<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeCommentRequest;
use App\Http\Requests\updateCommentRequest;
use App\Services\CommentService;
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

    public function store(storeCommentRequest $request)
    {
        Auth::loginUsingId(1);

        $commentData = $request->all();
        $this->commentService->store($commentData);

        $data = [
            'message' => 'کامنت شما با موفقیت ذخیره شد.',
        ];
        return response()->json($data, 200);
    }

    public function update($id, updateCommentRequest $request)
    {
        Auth::loginUsingId(1);

        $commentData = $request->all();

        $comment = $this->commentService->getById($id);
        if (!$comment) {
            return response()->json(['message' => 'کامنت مورد نظر وجود ندارد.'], 404);
        }

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

        $this->commentService->update($comment, $commentData);

        $data = [
            'message' => 'کامنت شما با موفقیت به روز رسانی شد.',
        ];
        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        Auth::loginUsingId(1);

        $comment = $this->commentService->getById($id);
        if (!$comment) {
            return response()->json(['message' => 'کامنت مورد نظر وجود ندارد.'], 404);
        }

        if (!Auth::user()->is_admin) {
            return response()->json(['message' => 'شما دسترسی ندارید!'], 403);
        }

        $this->commentService->destroy($comment);
        return response()->json(['message' => 'کامنت مورد نظر حذف شد!'], 200);
    }

    public function like($id)
    {
        Auth::loginUsingId(1);

        $comment = $this->commentService->getById($id);
        if (!$comment) {
            return response()->json(['message' => 'کامنت مورد نظر وجود ندارد.'], 404);
        }

        $this->commentService->like($comment);

        return response()->json([], 200);
    }

    public function unlike($id)
    {
        Auth::loginUsingId(1);

        $comment = $this->commentService->getById($id);
        if (!$comment) {
            return response()->json(['message' => 'کامنت مورد نظر وجود ندارد.'], 404);
        }

        $this->commentService->unlike($comment);

        return response()->json([], 200);
    }
}

