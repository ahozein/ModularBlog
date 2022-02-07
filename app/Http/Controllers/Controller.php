<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function defineCrudPermissionsFor($resource)
    {
        $this->defineCustomPermissionFor($resource, 'view', ['index', 'show']);
        $this->defineCustomPermissionFor($resource, 'create', ['create', 'store']);
        $this->defineCustomPermissionFor($resource, 'edit', ['edit', 'update']);
        $this->defineCustomPermissionFor($resource, 'delete', ['destroy']);
    }

    protected function defineCustomPermissionFor($resource, $permission, array $method)
    {
        $this->middleware("can:{$resource}.{$permission}")->only($method);
    }
}
