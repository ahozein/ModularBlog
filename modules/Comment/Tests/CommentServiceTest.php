<?php

namespace Modules\Comment\Tests;

use Tests\TestCase;
use Modules\Post\Models\Post;
use Modules\Comment\Models\Comment;
use Modules\Comment\Services\CommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentServiceTest extends TestCase
{
    use RefreshDatabase;

    private const DB_NAME = 'comments';

    private $service;
    private $fakeComment;
    private $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CommentService::class);

        $this->post = Post::factory()->create();

        $this->fakeComment = Comment::factory(['comment' => '::Comment::'])->disapproved()->create();
    }

    // Start tests .............................................................


    /**
     * @test
     */
    public function a_comment_can_be_created_by_authenticated_user()
    {
        $this->service->store($this->post, '::Comment::');

        $this->assertDatabaseCount(self::DB_NAME, 2); // + fake in setUp
        $this->assertDatabaseHas(self::DB_NAME, [
            'comment' => '::Comment::',
            'commentable_type' => Post::class,
            'commentable_id' => $this->post->id,
        ]);
    }


    /**
     * @test
     */
    public function a_comment_can_be_have_a_reply()
    {
        $this->service->update($this->fakeComment, '::REPLY::');

        $this->assertDatabaseCount(self::DB_NAME, 2); // fakeComment + reply
        $this->assertDatabaseHas(self::DB_NAME, [
            "comment" => "::REPLY::",
            "commentable_type" => Comment::class,
            "commentable_id" => $this->fakeComment->id,
            "is_approved" => true,
        ]);
    }


    /**
     * @test
     */
    public function a_comment_can_be_deleted()
    {
        // Before Delete...
        $this->assertDatabaseCount(self::DB_NAME, 1);

        $this->service->destroy($this->fakeComment);

        // After Delete...
        $this->assertDatabaseCount(self::DB_NAME, 0);
        $this->assertDatabaseMissing(self::DB_NAME, [
            'comment' => '::Comment::',
            'commentable_type' => Post::class,
            'commentable_id' => $this->post->id,
        ]);
    }


    /**
     * @test
     */
    public function toggle_approved_method_must_works_fine()
    {
        // Disapproved in the setUp.
        $this->assertEquals($this->fakeComment->is_approved, false);

        $this->service->toggleApproved(clone $this->fakeComment);

        // True is mean approved.
        $this->assertEquals($this->fakeComment->refresh()->is_approved, true);
    }

}
