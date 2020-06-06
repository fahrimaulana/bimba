<?php

use Illuminate\Database\Seeder;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Permission;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::set('platform', 'Admin');
        Permission::withoutGlobalScopes()->whereScope('Admin')->delete();

        Permission::insert([
            ['scope' => 'Admin', 'group' => 'Setting', 'name' => 'change-preference', 'display_name' => 'Change Preference'],

            ['scope' => 'Admin', 'group' => 'User', 'name' => 'view-user-list', 'display_name' => 'View User List'],
            ['scope' => 'Admin', 'group' => 'User', 'name' => 'create-user', 'display_name' => 'Create User'],
            ['scope' => 'Admin', 'group' => 'User', 'name' => 'edit-user', 'display_name' => 'Edit User'],
            ['scope' => 'Admin', 'group' => 'User', 'name' => 'delete-user', 'display_name' => 'Delete User'],
            ['scope' => 'Admin', 'group' => 'User', 'name' => 'view-user-login-history', 'display_name' => 'View User Login History'],
            ['scope' => 'Admin', 'group' => 'User', 'name' => 'change-user-password', 'display_name' => 'Change User Password'],

            ['scope' => 'Admin', 'group' => 'Role', 'name' => 'view-role-list', 'display_name' => 'View Role List'],
            ['scope' => 'Admin', 'group' => 'Role', 'name' => 'create-role', 'display_name' => 'Create Role'],
            ['scope' => 'Admin', 'group' => 'Role', 'name' => 'edit-role', 'display_name' => 'Edit Role'],
            ['scope' => 'Admin', 'group' => 'Role', 'name' => 'delete-role', 'display_name' => 'Delete Role'],

            /* Other Permissions */
        ]);

        if ($role = Role::first()) $role->syncPermissions(Permission::all());
    }
}
