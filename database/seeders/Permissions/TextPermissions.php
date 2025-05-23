<?php

namespace Database\Seeders\Permissions;

use App\Policies\TextPolicy;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TextPermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Possible actions one might take
        $actions = (object) TextPolicy::$actions;

        // create permissions
        Permission::create(['name' => $actions->view]);
        Permission::create(['name' => $actions->create]);
        Permission::create(['name' => $actions->delete]);
        Permission::create(['name' => $actions->update]);
        Permission::create(['name' => $actions->publish]);
        Permission::create(['name' => $actions->unpublish]);

        /**
         * create roles and assign existing permissions
         */

        // admin can do anything
        $role1 = Role::findByName('admin');
        foreach ($actions as $doAction) {
            $role1->givePermissionTo($doAction);
        }

        // student can only view
        $role2 = Role::findByName('student');
        $role2->givePermissionTo($actions->view);
    }
}
