<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(): JsonResponse
    {
        $posts = Cache::remember('posts.index', now()->addHour(), function () {
            return $this->postService->getAll();
        });

        return response()->json($posts);
    }

    public function show(int $id): JsonResponse
    {
        $post = $this->postService->getById($id);
        return response()->json($post);
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $postData = $request->except('category_id');
        $this->postService->store($request->category_id, $postData);
        Cache::forget('posts.index');

        return response()->json(['message' => 'پست با موفقیت افزوده شد.']);
    }

    public function update(int $id, UpdatePostRequest $request): JsonResponse
    {
        $postData = $request->except('category_id');

        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        if (Auth::id() !== $post->author_id && !Auth::user()->is_admin) {
            return response()->json(['message' => 'شما مجوز لازم برای به روز رسانی این پست را ندارید.'], 403);
        }

        if (isset($postData['is_published'])) {
            if (!Auth::user()->is_admin) {
                return response()->json(['message' => 'شما مجوز لازم برای به روز رسانی این پست را ندارید.'], 403);
            } else {
                $postData['is_published'] = (bool)$postData['is_published'];
                $postData['published_at'] = $postData['is_published'] ? now() : null;
            }
        }

        $this->postService->update($post, $request->category_id, $postData);
        Cache::forget('posts.index');

        return response()->json(['message' => 'پست با موفقیت به روز رسانی شد.']);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!Auth::user()->is_admin) {
            return response()->json(['message' => 'شما دسترسی ندارید!'], 403);
        }

        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        $this->postService->destroy($post);
        return response()->json(['message' => 'پست مورد نظر حذف شد!']);
    }

    public function like(int $id): JsonResponse
    {
        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        $this->postService->like($post);

        return response()->json();
    }

    public function unlike(int $id): JsonResponse
    {
        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        $this->postService->unlike($post);

        return response()->json();
    }

    public function bookmark(int $id): JsonResponse
    {
        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        $this->postService->bookmark($post);

        return response()->json();
    }

    public function unbookmark(int $id): JsonResponse
    {
        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        $this->postService->unbookmark($post);

        return response()->json();
    }
}

