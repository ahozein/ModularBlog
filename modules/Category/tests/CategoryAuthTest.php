<?php

namespace Modules\Category\Tests;

use Tests\TestCase;
use Tests\HasAuthTest;
use Modules\Category\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryAuthTest extends TestCase
{
    use RefreshDatabase, HasAuthTest;

    private const RESOURCE_ROUTE = 'dashboard.categories.';
    private $category;
    private $categoryId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
        $this->categoryId = $this->category->id;
    }

    // Start the tests ........................................................

    /**
     * @test
     */
    public function unauthenticated_user_can_not_access_to_any_category_resources()
    {
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'index',);
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'show', $this->categoryId);
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'create');
        $this->assertRedirectToLogin('post', self::RESOURCE_ROUTE . 'store');
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'edit', $this->categoryId);
        $this->assertRedirectToLogin('put', self::RESOURCE_ROUTE . 'update', $this->categoryId);
        $this->assertRedirectToLogin('delete', self::RESOURCE_ROUTE . 'destroy', $this->categoryId);

        // Assert data has yet...
        $this->assertDatabaseCount($this->category->getTable(), 1);
        $this->assertDatabaseHas($this->category->getTable(), $this->category->getAttributes());
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_read_categories()
    {
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'index');
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'show', $this->categoryId);
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_create_a_category()
    {
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'create');
        $this->assertNeedPermission('post', self::RESOURCE_ROUTE . 'store', []);
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_update_a_category()
    {
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'edit', $this->categoryId);
        $this->assertNeedPermission('put', self::RESOURCE_ROUTE . 'update', $this->categoryId);
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_delete_a_category()
    {
        $this->assertNeedPermission('delete', self::RESOURCE_ROUTE . 'destroy', $this->categoryId);
    }

}
