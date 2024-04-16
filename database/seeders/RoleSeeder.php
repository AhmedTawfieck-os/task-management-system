<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [ ['name' => 'manager', 'guard_name' => 'api'],
                   ['name' => 'user', 'guard_name' => 'api'] ];

        Role::insert($roles);
        //
    }
}
