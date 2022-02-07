<?php

namespace Modules\Post\Tests;

use Tests\TestCase;
use Tests\HasValidationTest;

use Modules\Post\Models\Post;
use Illuminate\Http\UploadedFile;
use Modules\Post\Requests\PostStoreRequest;
use Modules\Post\Requests\PostUpdateRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostValidationTest extends TestCase
{
    use RefreshDatabase, HasValidationTest;

    private $storeFormRequest;
    private $updateFormRequest;
    private $requestData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->storeFormRequest = new PostStoreRequest;
        $this->updateFormRequest = new PostUpdateRequest;

        $this->requestData = [
            'title' => '::Title::',
            'text' => '::LongText::',
            'status' => Post::DRAFT_STATUS,
            'image' => UploadedFile::fake()->image('fakePostImg.jpg'),
            'category_id' => $this->getRelatatedModel(Post::class, 'category')->id,
        ];
    }

    // Start the tests......................................................................

    /**
     * @test
     */
    public function post_title_is_required()
    {
        unset($this->requestData['title']);

        $this->assertFails($this->requestData, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function post_title_must_be_more_than_minimum_character()
    {
        $data = array_merge($this->requestData, ['title' => 'ab']);

        $this->assertFails($data, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function post_text_is_required()
    {
        unset($this->requestData['text']);

        $this->assertFails($this->requestData, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function category_id_is_required_for_each_post()
    {
        unset($this->requestData['category_id']);

        $this->assertFails($this->requestData, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function post_status_is_required()
    {
        // DRAFT or PUBLISHED STATUS
        unset($this->requestData['status']);

        $this->assertFails($this->requestData, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function image_is_nullable()
    {
        unset($this->requestData['image']);

        $this->assertPasses($this->requestData, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function post_banner_must_be_an_image()
    {
        $data = array_merge($this->requestData,
            ['image' => UploadedFile::fake()->create('file.pdf')]
        );

        $this->assertFails($data, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function The_image_must_be_less_than_5000_kilobytes()
    {
        $data = array_merge($this->requestData,
            ['image' => UploadedFile::fake()->create('file.jpg',5001)]
        );

        $this->assertFails($data, $this->storeFormRequest);
    }


}
