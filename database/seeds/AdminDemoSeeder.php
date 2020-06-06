<?php

use App\Models\Preference;
use Illuminate\Database\Seeder;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Admin;
use App\Models\UserManagement\Permission;

class AdminDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::set('platform', 'Admin');

        DB::select('SET FOREIGN_KEY_CHECKS=0;');
        Admin::wherePlatform(platform())->forceDelete();
        Role::wherePlatform(platform())->forceDelete();
        Preference::withoutGlobalScope('platform')->wherePlatform(platform())->forceDelete();
        DB::select('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker\Factory::create('id_ID');

        $admin = new Role;
        $admin->name = 'admin';
        $admin->display_name = 'Administrator';
        $admin->platform = 'Admin';
        $admin->save();
        $admin->attachPermissions(Permission::all());

        $user = new Admin;
        $user->name = 'Jimmy Setiawan';
        $user->email = 'admin@admin.com';
        $user->username = 'admin';
        $user->password = bcrypt('123123');
        $user->platform = 'Admin';
        $user->active = 1;
        $user->save();
        $user->attachRole($admin);

        Preference::insert([
            [
                'key' => 'logo',
                'value' => url('assets/images/logo.png'),
                'platform' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'key' => 'phone',
                'value' => '+ 62 21 7388 1188',
                'platform' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'key' => 'email',
                'value' => 'email@email.com',
                'platform' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
