<?php

namespace Modules\Post\Tests;

use Tests\TestCase;

use App\Models\User;

use Modules\Post\Models\Post;
use Illuminate\Http\UploadedFile;
use Modules\Category\Models\Category;
use Modules\Post\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostServiceTest extends TestCase
{
    use RefreshDatabase;

    private const DB_NAME = 'posts';

    private $service;
    private $fakePost;
    private $requestData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PostService::class);

        $this->fakePost = Post::factory()->create();

        $this->requestData = [
            'title' => '::Title::',
            'text' => '::LongText::',
            'status' => Post::DRAFT_STATUS,
            'image' => UploadedFile::fake()->image('fakePostImg.jpg'),
            'category_id' => Category::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
        ];
    }

    // Start tests .............................................................

// TODO Refactor this test when FrontEnd is ready!

//    /**
//     * @test
//     */
//    public function index_method_must_works_fine()
//    {
//        $this->assertJson($this->service->index());
//    }

    /**
     * @test
     */
    public function a_post_can_be_created()
    {
        $image = $this->requestData['image'];

        // Because table posts has no column named image.
        unset($this->requestData['image']);

        $this->service->store($this->requestData, $image);

        $this->assertDatabaseCount(self::DB_NAME, 2); // + fake in setUp
        $this->assertDatabaseHas(self::DB_NAME, $this->requestData);

        $this->assertDatabaseCount('media', 1);
        $this->assertDatabaseHas('media', [
            'file_name' => 'fakePostImg.jpg',
            'model_type' => Post::class,
            'model_id' => Post::where('title', '::Title::')->first()->id
        ]);
    }


    /**
     * @test
     */
    public function a_post_can_be_created_without_image()
    {
        unset($this->requestData['image']);

        $this->service->store($this->requestData, []);

        $this->assertDatabaseCount(self::DB_NAME, 2); // + fake in setUp
        $this->assertDatabaseHas(self::DB_NAME, $this->requestData);
        $this->assertDatabaseCount('media', 0);
    }


    /**
     * @test
     */
    public function a_post_can_be_updated()
    {
        $this->service->update($this->fakePost, [
            'title' => '::New Title::',
            'text' => '::New Text::',
            'status' => Post::PUBLISHED_STATUS,
            'category_id' => Category::factory()->create()->id,
        ], []);

        $this->assertDatabaseCount(self::DB_NAME, 1);
        $this->assertDatabaseHas(self::DB_NAME, ['title' => '::New Title::']);
    }


    /**
     * @test
     */
    public function has_image_method_must_works_fine()
    {
        // Because fakePost that we have in the setUp, dont has any images, So...
        $this->assertFalse($this->service->hasImage($this->fakePost));
    }


    /**
     * @test
     */
    public function a_post_can_be_deleted()
    {
        $this->service->destroy($this->fakePost);

        $this->assertSoftDeleted($this->fakePost);
        $this->assertDatabaseMissing(self::DB_NAME, $this->fakePost->toArray());
    }


    /**
     * @test
     */
    public function toggle_status_method_must_works_fine()
    {
        $post = Post::factory(['status' => Post::DRAFT_STATUS,])->create();
        
        // preventing from missing with object references.
        $this->service->toggleStatus(clone $post);

        $this->assertEquals($post->refresh()->status, Post::PUBLISHED_STATUS);
    }

}
