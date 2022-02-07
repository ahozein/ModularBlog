<?php

namespace Modules\Category\Tests;

use Tests\TestCase;
use Tests\HasValidationTest;
use Modules\Category\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Category\Requests\CategoryStoreRequest;
use Modules\Category\Requests\CategoryUpdateRequest;

class CategoryValidationTest extends TestCase
{
    use RefreshDatabase, HasValidationTest;

    private $requestData;
    private $storeFormRequest;
    private $updateFormRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->storeFormRequest = new CategoryStoreRequest;
        $this->updateFormRequest = new CategoryUpdateRequest;

        $this->requestData = [
            'name' => '::Category::',
            'parent_id' => null
        ];
    }

    /** @test */
    public function category_name_is_required()
    {
        unset($this->requestData['name']);

        $this->assertFails($this->requestData, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function category_name_must_be_unique()
    {
        Category::factory(['name' => '::Category::'])->create();
        $this->assertFails($this->requestData, $this->storeFormRequest);
    }

    /** @test */
    public function parent_id_is_nullable()
    {
        $this->assertPasses($this->requestData, $this->storeFormRequest);
    }


    /** @test */
    public function valid_attributes_should_pass_the_validator()
    {
        $this->assertPasses($this->requestData, $this->storeFormRequest);
    }

}
