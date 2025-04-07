<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\CategoryStatus;
use App\Models\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll(): Collection
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

    public function destroy($category): void
    {
        $this->categoryRepository->delete($category);
    }
}
