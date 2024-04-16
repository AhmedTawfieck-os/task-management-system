<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user= User::create([
            'name' => 'first-user', 
            'email' => 'firstuser@gmail.com', 
            'password' => Hash::make(87654321)
        ]);

        $user->assignRole('user');

        //Suggested to generate other 10 users of role user through user factory
        User::factory()->count(10)->create()->each(function ($user) {
            $user->assignRole('user'); 
        });
    }
}
