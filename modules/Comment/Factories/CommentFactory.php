<?php

namespace Modules\Comment\Factories;

use App\Models\User;
use Modules\Post\Models\Post;
use Modules\Comment\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment' => $this->faker->text(),
            'commentable_type' => Post::class,
            'commentable_id' => Post::factory()->create(),
            'user_id' => User::factory()->create(),
            'is_approved' => $this->faker->randomElement([0,1]),
        ];
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_approved' => 1,
            ];
        });
    }

    public function disapproved()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_approved' => 0,
            ];
        });
    }
}
