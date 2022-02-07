<?php


namespace Modules\Category\Services;


use Modules\Category\Models\Category;

class CategoryService
{
    /*
     * @param $attrubutes = [
     *      'name' => 'required | unique:categories,name' category name,
            'parent_id' => 'nullable'
     * ]
     */

    public function getLatest()
    {
        return Category::latest()->get();
    }

    public function store($attributes)
    {
        return Category::create($attributes);
    }

    public function update(Category $category, $attributes)
    {
        return $category->update($attributes);
    }

    public function hasChildren(Category $category)
    {
        return $category->children()->exists();
    }

    public function destroy(Category $category)
    {
        return $category->delete();
    }
}
