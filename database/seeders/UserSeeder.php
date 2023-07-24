<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Employee1',
            'email' => 'employee1@gmail.com',
            'password' => bcrypt('employee1@gmail.com')
        ]);

        User::create([
            'name' => 'Employee2',
            'email' => 'employee2@gmail.com',
            'password' => bcrypt('employee2@gmail.com')
        ]);
    }
}
