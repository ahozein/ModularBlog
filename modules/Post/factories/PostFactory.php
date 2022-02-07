<?php

namespace Modules\Post\factories;

use Modules\Post\Models\Post;
use Database\Factories\WithRelations;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    use WithRelations;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'text' => $this->faker->text(),
            'status' => $this->faker->randomElement(Post::STATUSES),
        ];
    }

    protected function relations()
    {
        return [
            'category_id' => 'category',
            'user_id' => 'user'
        ];
    }
}
