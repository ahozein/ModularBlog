<?php

namespace Modules\Role\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService
{

    public function getQuery()
    {
        $permissions = Permission::get();
        $roles = Role::latest()->get();

        return [$permissions, $roles];
    }

    /*
        * @param $attrubutes = [
        *      'name' => 'required | unique:roles,name' role name,
               'permissions' => 'required | array'
        * ]
        */
    public function store($name, array $permissions)
    {
        $role = Role::create($name);
        $role->syncPermissions($permissions);

        return $role;
    }

    /*
           * @param $attrubutes = [
           *      'name' => 'required | unique:roles,name' role->id,
                  'permissions' => 'required | array'
           * ]
           */
    public function update(Role $role, $name, array $permissions)
    {
        $role->update($name);
        $role->syncPermissions($permissions);

        return $role;
    }


    public function destroy(Role $role)
    {
        return $role->delete();
    }

}
