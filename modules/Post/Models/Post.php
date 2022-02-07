<?php

namespace Modules\Post\Models;

use App\Models\Like;
use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Modules\Post\factories\PostFactory;
use BeyondCode\Comments\Traits\HasComments;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Imanghafoori\Relativity\DynamicRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model implements HasMedia
{
    use SoftDeletes, HasFactory, InteractsWithMedia, HasComments, DynamicRelations;

    protected $guarded = [];

    public const MEDIA_COLLECTION_NAME = 'post-cover';

    public const PUBLISHED_STATUS = 1;
    public const DRAFT_STATUS = 0;

    public const STATUSES = [
      'PUBLISHED' => self::PUBLISHED_STATUS,
      'DRAFT' => self::DRAFT_STATUS
    ];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION_NAME)
        ->singleFile();
    }

    public function toggleStatus()
    {
        if ($this->status == self::DRAFT_STATUS) {
            $this->status = self::PUBLISHED_STATUS;
        } else {
            $this->status = self::DRAFT_STATUS;
        }

        return $this;
    }


    //--------------------- Relation Methods -----------------------\\
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
