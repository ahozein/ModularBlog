<?php

namespace Modules\Comment\Tests;

use Tests\TestCase;
use Modules\Post\Models\Post;
use Modules\Comment\Models\Comment;
use Modules\Comment\Services\CommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private const RESOURCE_NAME = 'comment';

    private const DASHBOARD_ROUTE = 'dashboard.comments.';
    private const USER_ROUTE = 'post.comment.';

    /**
     * @var MockInterface
     */
    private $commentServiceSpy;
    private $commentId;
    private $postId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commentServiceSpy = $this->spy(CommentService::class);

        $this->postId = Post::factory()->create()->id;

        $this->commentId = Comment::factory()->create()->id;
    }

    // Start the tests......................................................................


    /**
     * @test
     */
    public function index_method_must_works_fine()
    {
        //TODO Edit comments of this Class when FrontEnd is ready(Comment)!
//        $this->commentServiceSpy->shouldReceive('index')->once();

        $this->giveViewingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)->get(route(self::DASHBOARD_ROUTE . 'index'))
//            ->assertViewIs('dashboard.posts.index')
//            ->assertViewHas('posts')
            ->assertOk();
    }


    /**
     * @test
     */
    public function store_method_must_works_fine() // Authnticated...
    {
        $this->commentServiceSpy->shouldReceive('store')->once();

        $this->actingAs($this->unauthorized_user)
            ->post(route(self::USER_ROUTE . 'store', $this->postId), ['comment' => '::Comment::'])
            ->assertRedirect(route(self::USER_ROUTE . 'show', $this->postId))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('alert');
    }


    /**
     * @test
     */
    public function show_method_must_works_fine()
    {
        $this->actingAs($this->authorized_user)->get(route(self::USER_ROUTE . 'show', $this->postId))
//            ->assertViewIs('dashboard.category.show')
//            ->assertViewHas('category')
            ->assertOk();
    }


    /**
     * @test
     */
    public function edit_method_must_works_fine()
    {
        $this->giveCustomPermissionFor(self::RESOURCE_NAME,'reply');

        $this->actingAs($this->authorized_user)->get(route(self::DASHBOARD_ROUTE . 'edit', $this->commentId))
//            ->assertViewIs('dashboard.category.edit')
//            ->assertViewHas('category')
            ->assertOk();
    }


    /**
     * @test
     */
    public function update_method_must_works_fine()
    {
        $this->commentServiceSpy->shouldReceive('update')->once();

        $this->giveCustomPermissionFor(self::RESOURCE_NAME,'reply');

        $this->actingAs($this->authorized_user)
            ->put(route(self::DASHBOARD_ROUTE . 'update', $this->commentId), ['reply' => '::Reply::'])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route(self::DASHBOARD_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }


    /**
     * @test
     */
    public function destroy_method_must_works_fine()
    {
        $this->commentServiceSpy->shouldReceive('destroy')->once();

        $this->giveDeletingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)
            ->delete(route(self::DASHBOARD_ROUTE . 'destroy', $this->commentId))
            ->assertRedirect(route(self::DASHBOARD_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }


    /**
     * @test
     */
    public function toggle_approved_method_must_works_fine()
    {
        $this->commentServiceSpy->shouldReceive('toggleApproved')->once();

        $this->giveCustomPermissionFor(self::RESOURCE_NAME, 'toggleApproved');

        $this->actingAs($this->authorized_user)
            ->post(route(self::DASHBOARD_ROUTE . 'toggleApproved', $this->commentId))
            ->assertRedirect(route(self::DASHBOARD_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }
}
