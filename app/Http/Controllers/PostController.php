<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(): JsonResponse
    {
        $request = request();
        $page = $request->get('page', 1);
        $perPage = $request->get('perPage', 10);
        $posts = $this->postService->getAll($page, $perPage);

        return response()->json($posts);
    }

    public function show(int $id): JsonResponse
    {
        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        $post = $this->postService->show($post);

        return response()->json($post)->withCookie(cookie('post_viewed_' . $post->id, time(), 60 * 24));
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $postData = $request->except('category_id');
        $this->postService->store($request->category_id, $postData);

        return response()->json(['message' => 'پست با موفقیت افزوده شد.']);
    }

    public function update(int $id, UpdatePostRequest $request): JsonResponse
    {
        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        if (Auth::id() !== $post->author_id && !Auth::user()->is_admin) {
            return response()->json(['message' => 'شما مجوز لازم برای به روز رسانی این پست را ندارید.'], Response::HTTP_FORBIDDEN);
        }

        $postData = $request->except('category_id');

        if (isset($postData['status']) && ($postData['status'] === 'published' || $postData['status'] === 'rejected')) {
            if (!Auth::user()->is_admin) {
                return response()->json(['message' => 'شما مجوز لازم برای به روز رسانی این پست را ندارید.'], Response::HTTP_FORBIDDEN);
            }

            $postData['published_at'] = $postData['status'] === 'published' ? now() : null;
        }

        $this->postService->update($post, $request->category_id, $postData);

        return response()->json(['message' => 'پست با موفقیت به روز رسانی شد.']);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!Auth::user()->is_admin) {
            return response()->json(['message' => 'شما دسترسی ندارید!'], Response::HTTP_FORBIDDEN);
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

