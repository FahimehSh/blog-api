<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeCategoryRequest;
use App\Http\Requests\updateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = Cache::remember('categories.index', now()->addHour(), function () {
            return $this->categoryService->getAll();
        });

        return response()->json($categories);
    }

    public function show($id)
    {
        $category = $this->categoryService->getById($id);
        return response()->json($category);
    }

    public function store(storeCategoryRequest $request)
    {
        Auth::loginUsingId(1);

        if (!Auth::user()->is_admin) {
            return response()->json(['message' => 'شما مجوز لازم را ندارید.'], 403);
        }

        $categoryData = $request->all();
        $this->categoryService->store($categoryData);
        Cache::forget('categories.index');

        $data = [
            'message' => 'دسته بندی جدید اضافه شد.',
        ];

        return response()->json($data, 200);
    }

    public function update($id, updateCategoryRequest $request)
    {
        Auth::loginUsingId(1);

        if (!Auth::user()->is_admin) {
            return response()->json(['message' => 'شما مجوز لازم را ندارید.'], 403);
        }

        $category = $this->categoryService->getById($id);
        if (!$category) {
            return response()->json(['message' => 'دسته بندی مورد نظر وجود ندارد.'], 404);
        }

        $categoryData = $request->all();
        $this->categoryService->update($category, $categoryData);
        Cache::forget('categories.index');

        return response()->json(['message' => 'دسته بندی مورد نظر به روزرسانی شد.'], 200);
    }

    public function destroy($id)
    {
        Auth::loginUsingId(1);

        if (!Auth::user()->is_admin) {
            return response()->json(['message' => 'شما مجوز لازم را ندارید.'], 403);
        }

        $category = $this->categoryService->getById($id);
        if (!$category) {
            return response()->json(['message' => 'دسته بندی مورد نظر وجود ندارد.'], 404);
        }

        $category = $this->categoryService->getById($id);
        $this->categoryService->destroy($category);

        return response()->json(['message' => 'دسته بندی مورد نظر حذف شد!'], 200);
    }
}
