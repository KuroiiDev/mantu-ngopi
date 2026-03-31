<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = [
            'username' => 'ImTheBoss',
            'fullname' => 'Admin',
            'password' => bcrypt('admin123'),
            'role' => 'manager',
            
        ];
        User::insert($admin);
    }
}
