<?php

namespace Modules\Role\Controllers;

use Modules\Role\Services\RoleService;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Modules\Role\Requests\RoleStoreRequest;
use Modules\Role\Requests\RoleUpdateRequest;

class RoleController extends Controller
{

    function __construct()
    {
        $this->defineCrudPermissionsFor('role');
    }


    public function index(RoleService $roleService)
    {
        [$permissions, $roles] = $roleService->getQuery();

        return compact('roles', 'permissions');
    }


    public function create()
    {
        //
    }


    public function store(RoleStoreRequest $request, RoleService $roleService)
    {
        $roleService->store($request->only('name'), $request->input('permissions'));

        return redirect()->route('dashboard.roles.index')
            ->with(['alert' => 'نقش جدید با موفقیت ایجاد شد.']);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(RoleUpdateRequest $request, Role $role, RoleService $roleService)
    {
        $roleService->update($role, $request->only('name'), $request->input('permissions'));

        return redirect()->route('dashboard.roles.index')
            ->with(['alert' => 'نقش موردنظر با موفقیت ویرایش شد.']);
    }


    public function destroy(Role $role, RoleService $roleService)
    {
        $roleService->destroy($role);

        return redirect()->route('dashboard.roles.index')
            ->with(['alert' => 'نقش موردنظر با موفقیت حذف شد.']);
    }
}
