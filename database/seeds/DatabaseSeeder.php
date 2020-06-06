<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CalendarSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(AdminPermissionSeeder::class);
        $this->call(ClientPermissionSeeder::class);
        $this->call(AdminDemoSeeder::class);
        $this->call(ClientDemoSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(ProgressiveValuesTableSeeder::class);
    }
}
