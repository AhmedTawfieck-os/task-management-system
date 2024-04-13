<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ManagersAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $firstManager= User::create([
            'name' => 'first-manager', 
            'email' => 'firstmanager@gmail.com', 
            'password' => Hash::make(12345678)
        ]);

        $firstManager->assignRole('manager'); 

        $secondManager= User::create([
            'name' => 'second-manager', 
            'email' => 'secondmanager@gmail.com', 
            'password' => Hash::make(87654321)
        ]);

        $secondManager->assignRole('manager'); 

        $firstUser= User::create([
            'name' => 'first-user', 
            'email' => 'firstuser@gmail.com', 
            'password' => Hash::make(2468101214)
        ]);

        $firstUser->assignRole('user');

        $secondUser= User::create([
            'name' => 'second-user', 
            'email' => 'seconduser@gmail.com', 
            'password' => Hash::make(1412108642)
        ]);

        $secondUser->assignRole('user');
        //
    }
}
