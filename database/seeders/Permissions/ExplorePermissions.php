<?php

namespace Database\Seeders\Permissions;

use App\Policies\ExplorePolicy;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ExplorePermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Possible actions one might take
        $actions = (object) ExplorePolicy::$actions;

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

        // student can view lessons
        $role2 = Role::findByName('student');
        $role2->givePermissionTo($actions->view);
    }
}
