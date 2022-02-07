<?php

namespace Modules\Comment\Services;

use Modules\Post\Models\Post;
use Modules\Comment\Models\Comment;

class CommentService
{

    /*
    @param App\Models\Post $post
           string $comment_text
    */
    public function store(Post $post, string $comment_text)
    {
        return $comment = $post->comment($comment_text);
    }


    /*
    @param App\Models\Comment $comment
           string $reply
    */
    public function update(Comment $comment, string $reply)
    {
        $comment->comments()->delete();
        $comment->comment($reply);

        return $comment->comments()->first()->approve();
    }


    public function destroy(Comment $comment)
    {
        return $comment->delete();
    }


    public function toggleApproved(Comment $comment)
    {
        if ($comment->is_approved) {
            return $comment->disapprove();
        } else {
            return $comment->approve();
        }
    }


}
