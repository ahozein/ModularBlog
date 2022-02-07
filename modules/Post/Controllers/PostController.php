<?php

namespace Modules\Post\Controllers;

use App\Http\Controllers\Controller;
use Modules\Post\Requests\PostStoreRequest;
use Modules\Post\Requests\PostUpdateRequest;
use Modules\Post\Models\Post;

use Modules\Post\Services\PostService;

class PostController extends Controller
{

    public function __construct()
    {
        $this->defineCrudPermissionsFor('post');
        $this->defineCustomPermissionFor('post', 'toggleStatus', ['toggleStatus']);
    }


    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(PostStoreRequest $request, PostService $postService)
    {
        $postService->store($request->normalizedData(), $request->image);

        return redirect()->route('dashboard.posts.index')
            ->with(['alert' => 'پست جدید با موفقیت ایجاد شد.']);
    }


    public function show($id)
    {
        //
    }


    public function edit(Post $post)
    {
        //
    }


    public function update(PostUpdateRequest $request, Post $post, PostService $postService)
    {
        $postService->update($post, $request->normalizedData(), $request->image);

        return redirect()->route('dashboard.posts.index')
            ->with(['alert' => 'پست موردنظر با موفقیت ویرایش شد.']);
    }


    public function destroy(Post $post, PostService $postService)
    {
        $postService->destroy($post);

        return redirect()->route('dashboard.posts.index')
            ->with(['alert' => 'پست موردنظر با موفقیت حذف شد.']);
    }


    public function toggleStatus(Post $post, PostService $postService)
    {
        $postService->toggleStatus($post);

        return redirect()->route('dashboard.posts.index')
            ->with(['alert' => 'وضعیت نمایش پست موردنظر با موفقیت تغییر یافت.']);
    }
}
