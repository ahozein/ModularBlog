<?php

namespace Tests\Feature;

use Modules\Post\Models\Post;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    private $authenticated_user;
    private $postId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticated_user = User::factory()->create();
        $this->postId = Post::factory()->create()->id;
    }

    /**
     * @test
     */
    public function authenticated_user_can_like_a_post()
    {
        $this->actingAs($this->authenticated_user)
            ->post(route('toggleLike', ['post', $this->postId]));

        $this->assertDatabaseHas('likes', [
            'likeable_id' => $this->postId,
            'likeable_type' => Post::class,
            'user_id' => $this->authenticated_user->id
        ]);
    }

    /**
     * @test
     */
    public function unauthenticated_user_can_not_like_a_post()
    {
        $this->post(route('toggleLike', ['post', $this->postId]))
            ->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertDatabaseCount('likes', 0);
        $this->assertDatabaseMissing('likes', [
            'likeable_id' => $this->postId,
            'likeable_type' => Post::class,
        ]);
    }

    /**
     * @test
     */
    public function authenticated_user_can_dislike_a_post_that_liked_before()
    {
        // Like...
        $this->actingAs($this->authenticated_user)
            ->post(route('toggleLike', ['post', $this->postId]));

        $this->assertDatabaseHas('likes', [
            'likeable_id' => $this->postId,
            'likeable_type' => Post::class,
            'user_id' => $this->authenticated_user->id
        ]);

        // disLike...
        $this->actingAs($this->authenticated_user)
            ->post(route('toggleLike', ['post', $this->postId]));

        $this->assertDatabaseMissing('likes', [
            'likeable_id' => $this->postId,
            'likeable_type' => Post::class,
            'user_id' => $this->authenticated_user->id
        ]);
    }

}
