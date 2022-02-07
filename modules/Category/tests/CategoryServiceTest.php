<?php

namespace Modules\Category\Tests;

use Tests\TestCase;
use Modules\Category\Models\Category;
use Modules\Category\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private const DB_NAME = 'categories';
    private const REQUEST_DATA = [
        'name' => '::Category::',
        'parent_id' => null
    ];

    private $service;
    private $fake_category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CategoryService::class);

        $this->fake_category = Category::factory()->create();
    }

    // Start tests ..............................................

    /**
     * @test
     */
    public function get_latest_method_must_works_fine()
    {
        $this->assertJson($this->service->getLatest());
    }

    /** @test */
    public function a_category_can_be_created()
    {
        $this->service->store(self::REQUEST_DATA);

        $this->assertDatabaseCount(self::DB_NAME, 2); // + fake in setUp
        $this->assertDatabaseHas(self::DB_NAME, self::REQUEST_DATA);
    }

    /** @test */
    public function a_category_can_be_updated()
    {
        $this->assertTrue($this->service->update($this->fake_category, ['name' => '::New Category::']));

        $this->assertDatabaseCount(self::DB_NAME, 1);
        $this->assertDatabaseHas(self::DB_NAME, ['name' => '::New Category::']);
    }

    /** @test */
    public function a_category_can_be_deleted()
    {
        $this->service->destroy($this->fake_category);

        $this->assertDatabaseCount(self::DB_NAME, 0);
        $this->assertDatabaseMissing(self::DB_NAME, $this->fake_category->toArray());
    }

    /**
     * @test
     */
    public function has_children_must_works_fine()
    {
        $subCategory = [
            'name' => '::SubCategory::',
            'parent_id' => $this->fake_category->id,
        ];
        $this->service->store($subCategory);

        $this->assertTrue($this->service->hasChildren($this->fake_category));
    }

}
