<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Coffee'],
            ['name' => 'Snacks'],
            ['name' => 'Matcha'],
            ['name' => 'Frape'],
            ['name' => 'Juice'],
        ];

        foreach ($categories as $category) {
            Category::insert($category);
        }
    }
}
