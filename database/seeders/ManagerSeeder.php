<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $manager= User::create([
            'name' => 'first-manager', 
            'email' => 'firstmanager@gmail.com', 
            'password' => Hash::make(12345678)
        ]);

        $manager->assignRole('manager');
    }
}
