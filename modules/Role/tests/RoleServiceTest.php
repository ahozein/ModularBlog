<?php

namespace Modules\Role\Tests;

use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Modules\Role\Services\RoleService;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleServiceTest extends TestCase
{
    use RefreshDatabase;

    private const DB_NAME = 'roles';

    private $service;
    private $requestData;
    private $permissionIds;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(RoleService::class);

        $this->permissionIds = Permission::query()->inRandomOrder()->take(3)->pluck('id')->toArray();

        $this->requestData = [
            'name' => '::ROLE::',
            'permissions' => $this->permissionIds,
        ];
    }

    // Start tests .........................................................

    /**
     * @test
     */
    public function get_query_method_must_works_fine()
    {
        $this->assertIsArray($this->service->getQuery());
    }


    /**
     * @test
     */
    public function a_role_can_be_created()
    {
        $this->service->store(['name' => '::ROLE::'], ['permissions' => $this->permissionIds]);

        $role = Role::query()->where('name', '::ROLE::')->first();

        $this->assertDatabaseCount(self::DB_NAME, 2); // + fake in TestCase setUp!
        $this->assertDatabaseHas(self::DB_NAME, ['name' => '::ROLE::']);

        foreach ($this->permissionIds as $permissionId) {
            $this->assertDatabaseHas('role_has_permissions', [
                'role_id' => $role->id,
                'permission_id' => $permissionId,
            ]);
        }
    }


    /**
     * @test
     */
    public function a_role_can_be_updated()
    {
        // this->role already exists in the TestCase setUp.
        $this->service->update($this->role, ['name' => '::New Role::'], []);

        $this->assertDatabaseCount(self::DB_NAME, 1);
        $this->assertDatabaseHas(self::DB_NAME, ['name' => '::New Role::']);
    }


    /**
     * @test
     */
    public function a_role_can_be_deleted()
    {
        // this->role already exists in the TestCase setUp.
        $this->service->destroy($this->role);

        $this->assertDatabaseCount(self::DB_NAME, 0);
        $this->assertDatabaseMissing(self::DB_NAME, $this->role->toArray());

        foreach ($this->permissionIds as $permissionId) {
            $this->assertDatabaseMissing('role_has_permissions', [
                'role_id' => $this->role->id,
                'permission_id' => $permissionId,
            ]);
        }
    }


}
