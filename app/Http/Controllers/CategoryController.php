<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeCategoryRequest;
use App\Http\Requests\updateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAll();

        return response()->json($categories);
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->getById($id);
        return response()->json($category);
    }

    public function store(storeCategoryRequest $request): JsonResponse
    {
        $this->categoryService->store($request->createCategoryDTO->toArray());

        return response()->json(['message' => 'دسته بندی جدید اضافه شد.']);
    }

    public function update(int $id, updateCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->getById($id);
        if (!$category) {
            return response()->json(['message' => 'دسته بندی مورد نظر وجود ندارد.'], 404);
        }

        $categoryData = $request->all();
        $this->categoryService->update($category, $categoryData);

        return response()->json(['message' => 'دسته بندی مورد نظر به روزرسانی شد.']);
    }

    public function destroy(int $id): JsonResponse
    {
        $category = $this->categoryService->getById($id);
        if (!$category) {
            return response()->json(['message' => 'دسته بندی مورد نظر وجود ندارد.'], 404);
        }

        $category = $this->categoryService->getById($id);
        $this->categoryService->destroy($category);

        return response()->json(['message' => 'دسته بندی مورد نظر حذف شد!']);
    }
}
