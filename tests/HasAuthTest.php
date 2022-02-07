<?php

namespace Tests;

trait HasAuthTest
{
    protected function assertRedirectToLogin($method, $route_name, $route_parameters = [], $data = [])
    {
        $this->$method(route($route_name, $route_parameters), $data)
            ->assertRedirect(route('login'));
    }

    protected function assertNeedPermission($method, $route_name, $route_parameters = [], $data = [])
    {
        $this->actingAs($this->unauthorized_user)
            ->$method(route($route_name, $route_parameters), $data)
            ->assertForbidden();
    }
}
