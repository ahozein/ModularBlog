<?php

namespace Modules\Role\Tests;

use Tests\TestCase;
use Tests\HasValidationTest;
use Spatie\Permission\Models\Permission;
use Modules\Role\Requests\RoleStoreRequest;
use Modules\Role\Requests\RoleUpdateRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleValidationTest extends TestCase
{
    use RefreshDatabase, HasValidationTest;

    private $requestData;
    private $storeFormRequest;
    private $updateFormRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->storeFormRequest = new RoleStoreRequest;
        $this->updateFormRequest = new RoleUpdateRequest;

        $this->requestData = [
            'name' => '::Role::',
            'permissions' => Permission::query()
                ->inRandomOrder()->take(3)->pluck('id')->toArray(),
        ];
    }

    // Start the tests...................................................................

    /**
     * @test
     */
    public function role_name_is_required()
    {
        unset($this->requestData['name']);

        $this->assertFails($this->requestData, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function name_of_the_role_must_be_unique()
    {
        // this name already exist in the TestCase setUp.
        $data = array_merge($this->requestData, ['name' => 'fake-role']);

        $this->assertFails($data, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function permissions_id_is_required_for_create_a_role()
    {
        unset($this->requestData['permissions']);

        $this->assertFails($this->requestData, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function permission_id_must_be_type_of_an_array()
    {
        $data = array_merge($this->requestData, ['permissions' => 'st132']);

        $this->assertFails($data, $this->storeFormRequest);
    }

    /**
     * @test
     */
    public function request_data_should_pass_the_validator_for_create_a_role()
    {
        $this->assertPasses($this->requestData, $this->storeFormRequest);
    }

}
