<?php

namespace Tests;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Modules\Role\Seeders\PermissionTableSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $authorized_user;
    protected $unauthorized_user;
    protected $role;
    protected $permission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionTableSeeder::class,
        ]);

        $this->authorized_user = User::factory()->create();


        // this just for recognize the user to be admin and have not access to certen area
        $this->permission = Permission::create(['name' => 'fake-admin-permission']);

        $this->role = Role::create(['name' => 'fake-role'])
            ->givePermissionTo($this->permission);

        $this->unauthorized_user = User::factory()->create()
            ->assignRole($this->role);
    }

    /**
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function getRelatatedModel(string $model, string $relatation)
    {
        return app($model)->{$relatation}()->getRelated()->factory()->create();
    }

    // Permissions method......................................
    protected function giveViewingPermissionFor($resource)
    {
        $this->giveCustomPermissionFor($resource, 'view');

    }

    protected function giveCreatingPermissionFor($resource)
    {
        $this->giveCustomPermissionFor($resource, 'create');

    }

    protected function giveUpdatingPermissionFor($resource)
    {
        $this->giveCustomPermissionFor($resource, 'edit');

    }

    protected function giveDeletingPermissionFor($resource)
    {
        $this->giveCustomPermissionFor($resource, 'delete');
    }

    protected function giveCustomPermissionFor($resource, $custom_permission)
    {
        $this->authorized_user->givePermissionTo("{$resource}.{$custom_permission}");
    }
}
