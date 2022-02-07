<?php

namespace Modules\Category\Tests;

use Tests\TestCase;
use Mockery\MockInterface;
use Modules\Category\Models\Category;
use Modules\Category\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private const RESOURCE_NAME = 'category';
    private const RESOURCE_ROUTE = 'dashboard.categories.';
    private const REQUEST_DATA = [
        'name' => '::Category::',
        'parent_id' => null
    ];
    /**
     * @var MockInterface
     */
    private $categoryServiceSpy;
    private $categoryId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryServiceSpy = $this->spy(CategoryService::class);

        $this->categoryId = Category::factory()->create()->id;
    }

    // Start the tests......................................................................

    /** @test */
    public function index_method_must_works_fine()
    {
        $this->categoryServiceSpy->shouldReceive('getLatest')->once();

        $this->giveViewingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)->get(route(self::RESOURCE_ROUTE . 'index'))
//            ->assertViewIs('dashboard.categories.index') TODO Edit comments of here when FrontEnd is ready!
//            ->assertViewHas('categories')
            ->assertOk();
    }

    /** @test */
    public function create_method_must_works_fine()
    {
        $this->giveCreatingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)->get(route(self::RESOURCE_ROUTE . 'create'))
//            ->assertViewIs('dashboard.category.create')
            ->assertOk();
    }

    /** @test */
    public function store_method_must_works_fine()
    {
        $this->categoryServiceSpy->shouldReceive('store')->once();

        $this->giveCreatingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)
            ->post(route(self::RESOURCE_ROUTE . 'store'), self::REQUEST_DATA)
            ->assertRedirect(route(self::RESOURCE_ROUTE . 'index'));
    }

    /** @test */
    public function show_method_must_works_fine()
    {
        $this->giveViewingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)->get(route(self::RESOURCE_ROUTE . 'show', $this->categoryId))
//            ->assertViewIs('dashboard.category.show')
//            ->assertViewHas('category')
            ->assertOk();
    }

    /** @test */
    public function edit_method_must_works_fine()
    {
        $this->giveUpdatingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)->get(route(self::RESOURCE_ROUTE . 'edit', $this->categoryId))
//            ->assertViewIs('dashboard.category.edit')
//            ->assertViewHas('category')
            ->assertOk();
    }

    /** @test */
    public function update_method_must_works_fine()
    {
        $this->categoryServiceSpy->shouldReceive('update')->once();

        $this->giveUpdatingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)
            ->put(route(self::RESOURCE_ROUTE . 'update', $this->categoryId), self::REQUEST_DATA)
            ->assertRedirect(route(self::RESOURCE_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }


    /** @test */
    public function destroy_method_must_works_fine()
    {
        $this->categoryServiceSpy->shouldReceive('hasChildren')->once()->andReturnFalse();
        $this->categoryServiceSpy->shouldReceive('destroy')->once();

        $this->giveDeletingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)
            ->delete(route(self::RESOURCE_ROUTE . 'destroy', $this->categoryId))
            ->assertRedirect(route(self::RESOURCE_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }

    /**
     * @test
     */
    public function can_not_delete_a_category_that_has_children()
    {
        $this->categoryServiceSpy->shouldReceive('hasChildren')->once()->andReturnTrue();
        $this->categoryServiceSpy->shouldReceive('destroy')->never();

        $this->giveDeletingPermissionFor(self::RESOURCE_NAME);

        // Delete Category...
        $this->actingAs($this->authorized_user)
            ->delete(route(self::RESOURCE_ROUTE . 'destroy', $this->categoryId))
            ->assertRedirect(route(self::RESOURCE_ROUTE . 'index'))
            ->assertSessionHas('error');
    }

}
