<?php

namespace App\Services;

use App\Enums\CategoryStatus;
use App\Models\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll()
    {
        return $this->categoryRepository->getAll();
    }

    public function getById($id)
    {
        return $this->categoryRepository->getById($id);
    }

    public function store(array $categoryData)
    {
        return $this->categoryRepository->create($categoryData);
    }

    public function update($category, array $categoryData)
    {
        return $this->categoryRepository->update($category, $categoryData);
    }

    public function destroy($category)
    {
        $this->categoryRepository->delete($category);
    }
}
