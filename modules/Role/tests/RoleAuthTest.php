<?php

namespace Modules\Role\Tests;

use Tests\TestCase;
use Tests\HasAuthTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleAuthTest extends TestCase
{
    use RefreshDatabase, HasAuthTest;

    private const RESOURCE_ROUTE = 'dashboard.roles.';
    private $roleId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roleId = $this->role->id; // role already exist in TestCase setUp.
    }

    // Start the tests...................................................................

    /**
     * @test
     */
    public function unauthenticated_user_can_not_access_to_any_Role_resources()
    {
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'index');
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'show', $this->roleId);
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'create');
        $this->assertRedirectToLogin('post', self::RESOURCE_ROUTE . 'store');
        $this->assertRedirectToLogin('get', self::RESOURCE_ROUTE . 'edit', $this->roleId);
        $this->assertRedirectToLogin('put', self::RESOURCE_ROUTE . 'update', $this->roleId);
        $this->assertRedirectToLogin('delete', self::RESOURCE_ROUTE . 'destroy', $this->roleId);

        // assert data exists yet...
        $this->assertDatabaseCount($this->role->getTable(), 1);
        $this->assertDatabaseHas($this->role->getTable(), $this->role->getAttributes());
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_read_roles()
    {
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'index');
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'show', $this->roleId);
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_create_a_role()
    {
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'create');
        $this->assertNeedPermission('post', self::RESOURCE_ROUTE . 'store', []);
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_update_a_role()
    {
        $this->assertNeedPermission('get', self::RESOURCE_ROUTE . 'edit', $this->roleId);
        $this->assertNeedPermission('put', self::RESOURCE_ROUTE . 'update', $this->roleId);
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_delete_a_role()
    {
        $this->assertNeedPermission('delete', self::RESOURCE_ROUTE . 'destroy', $this->roleId);
    }
}
