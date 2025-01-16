<?php

namespace Database\Seeders;

use Database\Seeders\Permissions\ExplorePermissions;
use Database\Seeders\Permissions\LessonPermissions;
use Database\Seeders\Permissions\TextPermissions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Remove any roles that exist
        Permission::all()->each->forceDelete();
        Role::all()->each->forceDelete();

        // Create the roles the system needs
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'student']);

        DB::table('model_has_roles')->insert([
            'role_id' => '1',
            'model_type' => \App\Models\User::class,
            'model_id' => '1',
        ]);

        // Create the various permissions the system needs
        (new ExplorePermissions)->run();
        (new LessonPermissions)->run();
        (new TextPermissions)->run();
    }
}
