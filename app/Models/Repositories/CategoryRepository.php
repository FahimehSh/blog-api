<?php

namespace App\Models\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryRepository
{
    public function getAll(): Collection
    {
        return Cache::remember('categories.index', now()->addHour(), function () {
            return Category::all();
        });
    }

    public function getById($id)
    {
        return Category::query()->with('posts')->find($id);
    }

    public function create(array $categoryData)
    {
        $category = Category::query()->create($categoryData);
        Cache::forget('categories.index');
        return $category;
    }

    public function update($category, array $categoryData)
    {
        $category->update($categoryData);
        Cache::forget('categories.index');
        return $category;
    }

    public function delete($category): void
    {
        $category->posts()->delete();
        $category->delete();
    }
}
