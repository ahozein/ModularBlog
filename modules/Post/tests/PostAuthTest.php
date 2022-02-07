<?php

namespace Modules\Post\Tests;

use Modules\Post\Models\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\HasAuthTest;
use Tests\TestCase;

class PostAuthTest extends TestCase
{
    use RefreshDatabase, HasAuthTest;

    private const RESOURCE_ROUTE = 'dashboard.posts.';
    private $post;
    private $postId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->post = Post::factory()->create();
        $this->postId = $this->post->id;
    }

    // Start the tests...................................................................

    /**
     * @test
     */
    public function unauthenticated_user_can_not_access_to_any_post_resources()
    {
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'index');
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'show', $this->postId);
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'create');
        $this->assertRedirectToLogin('post', self::RESOURCE_ROUTE . 'store');
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'edit', $this->postId);
        $this->assertRedirectToLogin('put', self::RESOURCE_ROUTE . 'update', $this->postId);
        $this->assertRedirectToLogin('delete', self::RESOURCE_ROUTE . 'destroy', $this->postId);
        $this->assertRedirectToLogin('post', self::RESOURCE_ROUTE . 'toggleStatus', $this->postId);

        // assert data exists yet...
        $this->assertDatabaseCount($this->post->getTable(), 1);
        $this->assertDatabaseHas($this->post->getTable(), $this->post->getAttributes());
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_read_posts()
    {
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'index');
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'show', $this->postId);
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_create_a_post()
    {
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'create');
        $this->assertNeedPermission('post', self::RESOURCE_ROUTE . 'store', []);
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_update_a_post()
    {
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'edit', $this->postId);
        $this->assertNeedPermission('put', self::RESOURCE_ROUTE . 'update', $this->postId);
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_delete_a_post()
    {
        $this->assertNeedPermission('delete', self::RESOURCE_ROUTE . 'destroy', $this->postId);
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_toggle_status_of_a_post()
    {
        $this->assertNeedPermission('post', self::RESOURCE_ROUTE . 'toggleStatus', $this->postId);
    }

}
