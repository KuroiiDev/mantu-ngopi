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
        $users = [
            ['username' => 'manager', 'fullname' => 'Anggrek Mekar Pontianak', 'password' => bcrypt('admin123'), 'role' => 'manager'],
            ['username' => 'kasir', 'fullname' => 'Roti Lapis bandung', 'password' => bcrypt('admin123'), 'role' => 'cashier'],
            ['username' => 'logistik', 'fullname' => 'Uget Uget Boyolali', 'password' => bcrypt('admin123'), 'role' => 'logistic'],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
