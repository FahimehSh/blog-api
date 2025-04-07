<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $posts = Cache::remember('posts.index', now()->addHour(), function () {
            return $this->postService->getAll();
        });

        return response()->json($posts);
    }

    public function show($id)
    {
        $post = $this->postService->getById($id);
        return response()->json($post);
    }

    public function store(StorePostRequest $request)
    {
        Auth::loginUsingId(1);

        $postData = $request->except('category_id');
        $this->postService->store($request->category_id, $postData);
        Cache::forget('posts.index');

        $data = [
            'message' => 'پست با موفقیت افزوده شد.',
        ];
        return response()->json($data, 200);
    }

    public function update($id, UpdatePostRequest $request)
    {
        Auth::loginUsingId(1);

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
                $postData['is_published'] = $postData['is_published'] ? true : false;
                $postData['published_at'] = $postData['is_published'] ? now() : null;
            }
        }

        $this->postService->update($post, $request->category_id, $postData);
        Cache::forget('posts.index');

        $data = [
            'message' => 'پست با موفقیت به روز رسانی شد.',
        ];
        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        Auth::loginUsingId(1);

        if (!Auth::user()->is_admin) {
            return response()->json(['message' => 'شما دسترسی ندارید!'], 403);
        }

        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        $this->postService->destroy($post);
        return response()->json(['message' => 'پست مورد نظر حذف شد!'], 200);
    }

    public function like($id)
    {
        Auth::loginUsingId(1);

        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        $this->postService->like($post);

        return response()->json([], 200);
    }

    public function unlike($id)
    {
        Auth::loginUsingId(1);

        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        $this->postService->unlike($post);

        return response()->json([], 200);
    }

    public function bookmark($id)
    {
        Auth::loginUsingId(1);

        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        $this->postService->bookmark($post);

        return response()->json([], 200);
    }

    public function unbookmark($id)
    {
        Auth::loginUsingId(1);

        $post = $this->postService->getById($id);
        if (!$post) {
            return response()->json(['message' => 'پست مورد نظر وجود ندارد.'], 404);
        }

        $this->postService->unbookmark($post);

        return response()->json([], 200);
    }
}

