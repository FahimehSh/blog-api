<?php

namespace App\Models\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getAll()
    {
        return Category::all();
    }

    public function getById($id)
    {
        return Category::query()->with('posts')->find($id);
    }

    public function create(array $categoryData)
    {
        return Category::create($categoryData);
    }

    public function update($category, array $categoryData)
    {
        $category->update($categoryData);
        return $category;
    }

    public function delete($category)
    {
        $category->posts()->delete();
        $category->delete();
    }
}
