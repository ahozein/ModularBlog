<?php

namespace Modules\Comment\Tests;

use Tests\TestCase;
use Tests\HasValidationTest;
use Modules\Comment\Requests\CommentStoreRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Comment\Requests\CommentUpdateRequest;


class CommentValidationTest extends TestCase
{
    use RefreshDatabase, HasValidationTest;

    private $storeFormRequest;
    private $updateFormRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->storeFormRequest = new CommentStoreRequest;
        $this->updateFormRequest = new CommentUpdateRequest;
    }

    // Start the tests......................................................................

    /**
     * @test
     */
    public function comment_text_is_required()
    {
        $this->assertFails(['comment' => ''], $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function reply_text_is_required()
    {
        $this->assertFails(['reply' => ''], $this->updateFormRequest);
    }

}
