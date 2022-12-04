<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserPlace;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin Admin',
            // 'phone' => '09217529481',
            'phone' => '09555937444',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'role' => 1,
            'is_active' => 1,
            'address' => 'Admin'
        ]);

        // User::create([
        //     'name' => 'Client Client',
        //     'phone' => random_int(10000000000, 99999999999),
        //     'email' => 'client@gmail.com',
        //     'password' => Hash::make('password'),
        //     'user_type' => 'client',
        //     'role' => '2',
        //     'reference' => strtoupper(Str::random(8)) 
        // ]);
    }
}
