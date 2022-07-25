<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'first_name' => 'Mohamed',
                'last_name' => 'Juma',
                'username' => 'moh',
                'role' => 'super-admin',
                'email_verified_at' => now(),
                'email' => 'madyrio100@gmail.com',
                'password' => bcrypt('madyrio@100'),
            ], [
                'first_name' => 'Furaha',
                'last_name' => 'Mwangosi',
                'username' => 'mwangosi',
                'role' => 'doctor',
                'email_verified_at' => now(),
                'email' => 'mwangosi@gmail.com',
                'password' => bcrypt('mwangosi@100'),
            ]
        ];


        foreach ($users as $key => $user) {
            User::firstOrCreate(collect($user)->except('role')->toArray())->assignRole($user['role']);
        }
    }
}
