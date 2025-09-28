<?php

namespace Database\Seeders;

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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::all()->each->forceDelete();
        Role::all()->each->forceDelete();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'student']);

        DB::table('model_has_roles')->insert([
            'role_id' => '1',
            'model_type' => \App\Models\User::class,
            'model_id' => '1',
        ]);
    }
}
