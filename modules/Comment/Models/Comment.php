<?php

namespace Modules\Comment\Models;

use Modules\Comment\Factories\CommentFactory;
use BeyondCode\Comments\Comment as BaseComment;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends BaseComment
{
    use HasFactory;

        /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return CommentFactory::new();
    }

}
