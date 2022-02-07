<?php

namespace Modules\Post\Tests;

use Tests\TestCase;

use Mockery\MockInterface;

use Modules\Post\Models\Post;
use Illuminate\Http\UploadedFile;
use Modules\Category\Models\Category;
use Modules\Post\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private const RESOURCE_NAME = 'post';
    private const RESOURCE_ROUTE = 'dashboard.posts.';

    /**
     * @var MockInterface
     */
    private $postServiceSpy;
    private $requestData;
    private $postId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postServiceSpy = $this->spy(PostService::class);

        $this->postId = Post::factory()->create()->id;

        $this->requestData = [
            'title' => '::Title::',
            'text' => '::LongText::',
            'status' => Post::PUBLISHED_STATUS,
            'image' => UploadedFile::fake()->image('fakePostImg.jpg'),
            'category_id' => Category::factory()->create()->id
        ];
    }

    // Start the tests......................................................................

    /**
     * @test
     */
    public function index_method_must_works_fine()
    {
        //TODO Edit comments of this Class when FrontEnd is ready(Posts)!
//        $this->postServiceSpy->shouldReceive('getQuery')->once();

        $this->giveViewingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)->get(route(self::RESOURCE_ROUTE . 'index'))
//            ->assertViewIs('dashboard.posts.index')
//            ->assertViewHas('posts')
            ->assertOk();
    }


    /**
     * @test
     */
    public function create_method_must_works_fine()
    {
        $this->giveCreatingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)->get(route(self::RESOURCE_ROUTE . 'create'))
//            ->assertViewIs('dashboard.category.create')
            ->assertOk();
    }


    /**
     * @test
     */
    public function store_method_must_works_fine()
    {
        $this->postServiceSpy->shouldReceive('store')->once();

        $this->giveCreatingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)
            ->post(route(self::RESOURCE_ROUTE . 'store'), $this->requestData)
            ->assertRedirect(route(self::RESOURCE_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }


    /**
     * @test
     */
    public function store_method_must_works_fine_without_image()
    {
        $this->postServiceSpy->shouldReceive('store')->once();

        $this->giveCreatingPermissionFor(self::RESOURCE_NAME);

        unset($this->requestData['image']);

        $this->actingAs($this->authorized_user)
            ->post(route(self::RESOURCE_ROUTE . 'store'), $this->requestData)
            ->assertRedirect(route(self::RESOURCE_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }


    /**
     * @test
     */
    public function show_method_must_works_fine()
    {
        $this->giveViewingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)->get(route(self::RESOURCE_ROUTE . 'show', $this->postId))
//            ->assertViewIs('dashboard.category.show')
//            ->assertViewHas('category')
            ->assertOk();
    }


    /**
     * @test
     */
    public function edit_method_must_works_fine()
    {
        $this->giveUpdatingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)->get(route(self::RESOURCE_ROUTE . 'edit', $this->postId))
//            ->assertViewIs('dashboard.category.edit')
//            ->assertViewHas('category')
            ->assertOk();
    }


    /**
     * @test
     */
    public function update_method_must_works_fine()
    {
        $this->postServiceSpy->shouldReceive('update')->once();

        $this->giveUpdatingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)
            ->put(route(self::RESOURCE_ROUTE . 'update', $this->postId), $this->requestData)
            ->assertRedirect(route(self::RESOURCE_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }


    /**
     * @test
     */
    public function destroy_method_must_works_fine()
    {
        $this->postServiceSpy->shouldReceive('destroy')->once();

        $this->giveDeletingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)
            ->delete(route(self::RESOURCE_ROUTE . 'destroy', $this->postId))
            ->assertRedirect(route(self::RESOURCE_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }


    /**
     * @test
     */
    public function toggle_status_method_must_works_fine()
    {
        $this->postServiceSpy->shouldReceive('toggleStatus')->once();

        $this->giveCustomPermissionFor(self::RESOURCE_NAME, 'toggleStatus');

        $this->actingAs($this->authorized_user)
            ->post(route(self::RESOURCE_ROUTE . 'toggleStatus', $this->postId))
            ->assertRedirect(route(self::RESOURCE_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }

}
