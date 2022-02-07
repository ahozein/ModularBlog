<?php

namespace App\Http\Controllers;

use Modules\Post\Models\Post;


class LikeController extends Controller
{
    public function toggleLike($type, $id)
    {
        $type = [
            'post' => Post::class,
        ][$type];

        if (auth()->user()->likes()->whereLikeableType($type)->whereLikeableId($id)->exists()) {
            auth()->user()->likes()->whereLikeableType($type)->whereLikeableId($id)->delete();
        } else {
            auth()->user()->likes()->create([
                'likeable_id' => $id,
                'likeable_type' => $type,
            ]);
        }

        return back();
    }
}
