<?php

namespace Modules\Role\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',

            'category.view',
            'category.create',
            'category.edit',
            'category.delete',

            'post.view',
            'post.create',
            'post.edit',
            'post.delete',
            'post.toggleStatus',

            'comment.view',
            'comment.reply',
            'comment.delete',
            'comment.toggleApproved',

            'user.delete',
            'user.assignRole',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
