<?php

namespace Modules\Comment\Controllers;

use Modules\Post\Models\Post;
use Modules\Comment\Models\Comment;
use App\Http\Controllers\Controller;
use Modules\Comment\Services\CommentService;
use Modules\Comment\Requests\CommentStoreRequest;
use Modules\Comment\Requests\CommentUpdateRequest;

class CommentController extends Controller
{
    private const RESOURCE = 'comment';

    public function __construct()
    {
        $this->defineCustomPermissionFor(self::RESOURCE, 'view', ['index']);
        $this->defineCustomPermissionFor(self::RESOURCE, 'reply', ['edit', 'update']);
        $this->defineCustomPermissionFor(self::RESOURCE, 'toggleApproved', ['toggleApproved']);
        $this->defineCustomPermissionFor(self::RESOURCE, 'delete', ['destroy']);
    }


    public function index()
    {
        // Front-end...
    }


    public function store(Post $post, CommentStoreRequest $request, CommentService $commentService)
    {
        $commentService->store($post, $request->input('comment'));

        return redirect()->route('post.comment.show', $post->id)
            ->with('alert', 'نظر شما با موفقیت ثبت شد و پس از تائید نمایش داده می شود.');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Comment $comment, CommentUpdateRequest $request, CommentService $commentService)
    {
        $commentService->update($comment, $request->input('reply'));

        return redirect()->route('dashboard.comments.index')
            ->with('alert', 'پاسخ نظر بــا موفقیت ثبت شد.');
    }


    public function destroy(Comment $comment, CommentService $commentService)
    {
        $commentService->destroy($comment);

        return redirect()->route('dashboard.comments.index')
            ->with('alert', 'نظر بــا موفقیت حذف شد.');
    }


    public function toggleApproved(Comment $comment, CommentService $commentService)
    {
        $commentService->toggleApproved($comment);

        return redirect()->route('dashboard.comments.index')
            ->with('alert', 'وضعیت نظر بــا موفقیت تغییر کرد.');
    }
}
