<?php

namespace Modules\Category\Models;

use Modules\Post\Models\Post;

use Illuminate\Database\Eloquent\Model;
use Modules\Category\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function isRoot()
    {
        return $this->parent_id == null;
    }

    //--------------- Relation Methods ------------------//
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
