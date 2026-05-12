<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supply;

class SupplySeeder extends Seeder
{
    public function run(): void
    {
        $supplies = [
            // Kopi
            ['name' => 'Biji Kopi Arabika', 'qty' => 5, 'unit' => 'Kg', 'price' => 120000],
            ['name' => 'Biji Kopi Robusta', 'qty' => 8, 'unit' => 'Kg', 'price' => 80000],
            // Susu & Cream
            ['name' => 'Susu Full Cream', 'qty' => 10, 'unit' => 'L', 'price' => 18000],
            ['name' => 'Susu Oat', 'qty' => 4, 'unit' => 'L', 'price' => 35000],
            ['name' => 'Heavy Cream', 'qty' => 3, 'unit' => 'L', 'price' => 45000],
            // Sirup & Gula
            ['name' => 'Gula Pasir', 'qty' => 15, 'unit' => 'Kg', 'price' => 14000],
            ['name' => 'Gula Aren Cair', 'qty' => 6, 'unit' => 'L', 'price' => 28000],
            ['name' => 'Sirup Vanilla', 'qty' => 2, 'unit' => 'L', 'price' => 55000],
            ['name' => 'Sirup Hazelnut', 'qty' => 2, 'unit' => 'L', 'price' => 60000],
            ['name' => 'Sirup Caramel', 'qty' => 1, 'unit' => 'L', 'price' => 55000],
            // Teh
            ['name' => 'Teh Hijau', 'qty' => 3, 'unit' => 'Kg', 'price' => 90000],
            ['name' => 'Teh Earl Grey', 'qty' => 2, 'unit' => 'Kg', 'price' => 110000],
            // Coklat
            ['name' => 'Bubuk Coklat', 'qty' => 4, 'unit' => 'Kg', 'price' => 75000],
            ['name' => 'Dark Chocolate', 'qty' => 2, 'unit' => 'Kg', 'price' => 130000],
            // Lainnya
            ['name' => 'Es Batu', 'qty' => 20, 'unit' => 'Kg', 'price' => 5000],
            ['name' => 'Air Mineral', 'qty' => 50, 'unit' => 'L', 'price' => 3000],
            ['name' => 'Matcha Powder', 'qty' => 1, 'unit' => 'Kg', 'price' => 200000],
            ['name' => 'Whipped Cream', 'qty' => 3, 'unit' => 'L', 'price' => 40000],
            // Makanan
            ['name' => 'Tepung Terigu', 'qty' => 10, 'unit' => 'Kg', 'price' => 12000],
            ['name' => 'Mentega', 'qty' => 5, 'unit' => 'Kg', 'price' => 35000],
        ];

        foreach ($supplies as $supply) {
            Supply::create($supply);
        }
    }
}
