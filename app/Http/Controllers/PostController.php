<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $posts = $this->postService->getAll();
        return response()->json($posts);
    }

    public function show($id)
    {
        $post = $this->postService->getById($id);
        return response()->json($post);
    }

    public function store(StorePostRequest $request)
    {
        $postData = $request->all();
        $post = $this->postService->store($postData);
        return response()->json($post, 200);
    }

    public function update($id, Request $request)
    {
        $postData = $request->all();

        $post = $this->postService->getById($id);
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

        $post = $this->postService->update($post, $postData);
        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        if (Auth::user()->is_admin == false) {
            return response()->json(['message' => 'شما دسترسی ندارید!'], 403);
        }

        $this->postService->destroy($id);
        return response()->json(['message' => 'پست مورد نظر حذف شد!'], 200);
    }
}

