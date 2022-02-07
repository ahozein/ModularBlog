<?php

namespace Modules\Post\Services;

use Modules\Post\Models\Post;


class PostService
{
//    TODO Refactor when FrontEnd is ready!
//    public function index()
//    {
//
//    }


    /*
        * @param $attrubutes = [
            'title' => 'required | min:3',
            'text' => 'required',
            'category_id' => 'required',
            'image' => 'file | image | max:5000',
            'status' => 'required',
        * ]
        */
    public function store($attributes, $image)
    {
        $post = Post::create($attributes);

        //image is nullable...
        if ($image) {
            $post->addMedia($image)
                ->toMediaCollection(Post::MEDIA_COLLECTION_NAME);
        }

        return $post;
    }


    /*
        * @param $attrubutes = [
            'title' => 'required | min:3',
            'text' => 'required',
            'category_id' => 'required',
            'image' => 'file | image | max:5000',
            'status' => 'required',
        * ]
        */
    public function update(Post $post, $attributes, $image)
    {
        $post->update($attributes);

        //image is nullable...
        if ($image) {
            $post->addMedia($image)
                ->toMediaCollection(Post::MEDIA_COLLECTION_NAME);
        }

        return $post;
    }


    public function hasImage(Post $post)
    {
        return $post->hasMedia(Post::MEDIA_COLLECTION_NAME);
    }


    public function destroy(Post $post)
    {
        if ($this->hasImage($post)) {
            $post->clearMediaCollection(Post::MEDIA_COLLECTION_NAME);
        }

       return $post->delete();
    }


    public function toggleStatus(Post $post)
    {
        return $post->toggleStatus()->save();
    }
}
