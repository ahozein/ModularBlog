<?php

namespace Modules\Comment\Tests;

use Tests\TestCase;
use Tests\HasAuthTest;
use Modules\Post\Models\Post;
use Modules\Comment\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentAuthTest extends TestCase
{
    use RefreshDatabase, HasAuthTest;

    private const DASHBOARD_ROUTE = 'dashboard.comments.';
    private const USER_ROUTE = 'post.comment.';

    private $postId;
    private $comment;
    private $commentId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postId = Post::factory()->create()->id;

        $this->comment = Comment::factory()->create();
        $this->commentId = $this->comment->id;
    }

    // Start the tests...................................................................

    /**
     * @test
     */
    public function unauthenticated_user_can_not_access_to_any_comment_resources()
    {
        // User side that just has Auth middleware...
        $this->assertRedirectToLogin('post', self::USER_ROUTE . 'store', $this->postId);
        $this->assertRedirectToLogin('get', self::USER_ROUTE . 'show', $this->postId);

        // Dashboard side that has Admin and Auth middlewares...
        $this->assertRedirectToLogin('get', self::DASHBOARD_ROUTE . 'index');
        $this->assertRedirectToLogin('get', self::DASHBOARD_ROUTE . 'edit', $this->commentId);
        $this->assertRedirectToLogin('put', self::DASHBOARD_ROUTE . 'update', $this->commentId);
        $this->assertRedirectToLogin('delete', self::DASHBOARD_ROUTE . 'destroy', $this->commentId);
        $this->assertRedirectToLogin('post', self::DASHBOARD_ROUTE . 'toggleApproved', $this->commentId);

        // assert data exists yet...
        $this->assertDatabaseCount($this->comment->getTable(), 1);
        $this->assertDatabaseHas($this->comment->getTable(), $this->comment->getAttributes());
    }


    /**
     * @test
     */
    public function unauthorized_user_can_not_read_comments_in_the_dashboard()
    {
        $this->assertNeedPermission('get', self::DASHBOARD_ROUTE . 'index');
    }


    /**
     * @test
     */
    public function unauthorized_user_can_not_reply_on_a_comment()
    {
        $this->assertNeedPermission('get', self::DASHBOARD_ROUTE . 'edit', $this->commentId);
        $this->assertNeedPermission('put', self::DASHBOARD_ROUTE . 'update', $this->commentId);
    }


    /**
     * @test
     */
    public function unauthorized_user_can_not_delete_a_comment()
    {
        $this->assertNeedPermission('delete', self::DASHBOARD_ROUTE . 'destroy', $this->commentId);
    }


    /**
     * @test
     */
    public function unauthorized_user_can_not_toggle_approved_a_comment()
    {
        $this->assertNeedPermission('post', self::DASHBOARD_ROUTE . 'toggleApproved', $this->commentId);
    }

}
