<?php

namespace Modules\Role\Tests;

use Tests\TestCase;
use Mockery\MockInterface;
use Modules\Role\Services\RoleService;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    private const RESOURCE_NAME = 'role';
    private const RESOURCE_ROUTE = 'dashboard.roles.';

    /**
     * @var MockInterface
     */
    private $roleServiceSpy;
    private $requestData;
    private $roleId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roleServiceSpy = $this->spy(RoleService::class);

        $this->requestData = [
            'name' => '::ROLE::',
            'permissions' => Permission::query()
                ->inRandomOrder()->take(3)->pluck('id')->toArray(),
        ];

        // in the TestCase setUp...
        $this->roleId = $this->role->id;
    }

    // Start the tests......................................................................

    /**
     * @test
     */
    public function index_method_must_works_fine()
    {
        $this->roleServiceSpy->shouldReceive('getQuery')->once();

        $this->giveViewingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)->get(route(self::RESOURCE_ROUTE . 'index'))
//            ->assertViewIs('dashboard.categories.index') TODO Edit comments of here when FrontEnd is ready (Roles)!
//            ->assertViewHas('categories')
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
        $this->roleServiceSpy->shouldReceive('store')->once();

        $this->giveCreatingPermissionFor(self::RESOURCE_NAME);

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

        $this->actingAs($this->authorized_user)->get(route(self::RESOURCE_ROUTE . 'show', $this->roleId))
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

        $this->actingAs($this->authorized_user)->get(route(self::RESOURCE_ROUTE . 'edit', $this->roleId))
//            ->assertViewIs('dashboard.category.edit')
//            ->assertViewHas('category')
            ->assertOk();
    }


    /**
     * @test
     */
    public function update_method_must_works_fine()
    {
        $this->roleServiceSpy->shouldReceive('update')->once();

        $this->giveUpdatingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)
            ->put(route(self::RESOURCE_ROUTE . 'update', $this->roleId), $this->requestData)
            ->assertRedirect(route(self::RESOURCE_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }


    /**
     * @test
     */
    public function destroy_method_must_works_fine()
    {
        $this->roleServiceSpy->shouldReceive('destroy')->once();

        $this->giveDeletingPermissionFor(self::RESOURCE_NAME);

        $this->actingAs($this->authorized_user)
            ->delete(route(self::RESOURCE_ROUTE . 'destroy', $this->roleId))
            ->assertRedirect(route(self::RESOURCE_ROUTE . 'index'))
            ->assertSessionHas('alert');
    }

}
