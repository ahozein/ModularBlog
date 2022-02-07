<?php

namespace Modules\Category\Controller;

use App\Http\Controllers\Controller;
use Modules\Category\Models\Category;
use Modules\Category\Services\CategoryService;
use Modules\Category\Requests\CategoryStoreRequest;
use Modules\Category\Requests\CategoryUpdateRequest;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->defineCrudPermissionsFor('category');
    }


    public function index(CategoryService $categoryService)
    {
        return $categories = $categoryService->getLatest();
    }


    public function create()
    {
        //
    }


    public function store(CategoryStoreRequest $request, CategoryService $categoryService)
    {
        $categoryService->store($request->validated());
//        app(CategoryService::class)->store($request->validated());

        return redirect()->route('dashboard.categories.index');
    }


    public function show(Category $category)
    {
        //
    }


    public function edit(Category $category)
    {
        //
    }


    public function update(CategoryUpdateRequest $request, Category $category, CategoryService $categoryService)
    {
        $category = $categoryService->update($category, $request->validated());

        return redirect()->route('dashboard.categories.index')
            ->with(['alert' => 'دسته بندی موردنظر با موفقیت ویرایش شد.']);
    }


    public function destroy(Category $category, CategoryService $categoryService)
    {
        if ($categoryService->hasChildren($category)) {
            return redirect()->route('dashboard.categories.index')
                ->with(['error' => 'دسته بندی دارای زیر دسته را نمی توان پاک کرد.']);
        }

        $categoryService->destroy($category);

        return redirect()->route('dashboard.categories.index')
            ->with(['alert' => 'دسته بندی موردنظر با موفقیت حذف شد.']);
    }

}
