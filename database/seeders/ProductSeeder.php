<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supply;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $kopiPanas = Category::where('name', 'Kopi Panas')->first()->id;
        $kopiDingin = Category::where('name', 'Kopi Dingin')->first()->id;
        $nonKopi = Category::where('name', 'Non-Kopi')->first()->id;
        $makanan = Category::where('name', 'Makanan Ringan')->first()->id;
        $minuman = Category::where('name', 'Minuman Segar')->first()->id;

        $arabika = Supply::where('name', 'Biji Kopi Arabika')->first();
        $robusta = Supply::where('name', 'Biji Kopi Robusta')->first();
        $susufull = Supply::where('name', 'Susu Full Cream')->first();
        $susuoat = Supply::where('name', 'Susu Oat')->first();
        $heavycream = Supply::where('name', 'Heavy Cream')->first();
        $gula = Supply::where('name', 'Gula Pasir')->first();
        $gulaaren = Supply::where('name', 'Gula Aren Cair')->first();
        $vanilla = Supply::where('name', 'Sirup Vanilla')->first();
        $hazelnut = Supply::where('name', 'Sirup Hazelnut')->first();
        $caramel = Supply::where('name', 'Sirup Caramel')->first();
        $tehHijau = Supply::where('name', 'Teh Hijau')->first();
        $earlGrey = Supply::where('name', 'Teh Earl Grey')->first();
        $coklat = Supply::where('name', 'Bubuk Coklat')->first();
        $darkChoc = Supply::where('name', 'Dark Chocolate')->first();
        $es = Supply::where('name', 'Es Batu')->first();
        $air = Supply::where('name', 'Air Mineral')->first();
        $matcha = Supply::where('name', 'Matcha Powder')->first();
        $whipped = Supply::where('name', 'Whipped Cream')->first();
        $tepung = Supply::where('name', 'Tepung Terigu')->first();
        $mentega = Supply::where('name', 'Mentega')->first();

        $products = [
            // Kopi Panas
            [
                'name' => 'Espresso',
                'price' => 18000,
                'category_id' => $kopiPanas,
                'supplies' => [
                    [$arabika->id, 0.018, 'Kg'],
                ]
            ],
            [
                'name' => 'Americano',
                'price' => 22000,
                'category_id' => $kopiPanas,
                'supplies' => [
                    [$arabika->id, 0.018, 'Kg'],
                    [$air->id, 0.15, 'L'],
                ]
            ],
            [
                'name' => 'Cappuccino',
                'price' => 28000,
                'category_id' => $kopiPanas,
                'supplies' => [
                    [$arabika->id, 0.018, 'Kg'],
                    [$susufull->id, 0.15, 'L'],
                ]
            ],
            [
                'name' => 'Latte',
                'price' => 30000,
                'category_id' => $kopiPanas,
                'supplies' => [
                    [$arabika->id, 0.018, 'Kg'],
                    [$susufull->id, 0.2, 'L'],
                ]
            ],
            [
                'name' => 'Hazelnut Latte',
                'price' => 35000,
                'category_id' => $kopiPanas,
                'supplies' => [
                    [$arabika->id, 0.018, 'Kg'],
                    [$susufull->id, 0.2, 'L'],
                    [$hazelnut->id, 0.02, 'L'],
                ]
            ],
            [
                'name' => 'Caramel Macchiato',
                'price' => 35000,
                'category_id' => $kopiPanas,
                'supplies' => [
                    [$arabika->id, 0.018, 'Kg'],
                    [$susufull->id, 0.2, 'L'],
                    [$caramel->id, 0.02, 'L'],
                ]
            ],

            // Kopi Dingin
            [
                'name' => 'Iced Americano',
                'price' => 25000,
                'category_id' => $kopiDingin,
                'supplies' => [
                    [$robusta->id, 0.018, 'Kg'],
                    [$es->id, 0.15, 'Kg'],
                    [$air->id, 0.1, 'L'],
                ]
            ],
            [
                'name' => 'Iced Latte',
                'price' => 32000,
                'category_id' => $kopiDingin,
                'supplies' => [
                    [$robusta->id, 0.018, 'Kg'],
                    [$susufull->id, 0.2, 'L'],
                    [$es->id, 0.15, 'Kg'],
                ]
            ],
            [
                'name' => 'Kopi Susu Gula Aren',
                'price' => 28000,
                'category_id' => $kopiDingin,
                'supplies' => [
                    [$robusta->id, 0.018, 'Kg'],
                    [$susufull->id, 0.15, 'L'],
                    [$gulaaren->id, 0.03, 'L'],
                    [$es->id, 0.15, 'Kg'],
                ]
            ],
            [
                'name' => 'Oat Latte',
                'price' => 38000,
                'category_id' => $kopiDingin,
                'supplies' => [
                    [$arabika->id, 0.018, 'Kg'],
                    [$susuoat->id, 0.2, 'L'],
                    [$es->id, 0.15, 'Kg'],
                ]
            ],
            [
                'name' => 'Cold Brew',
                'price' => 30000,
                'category_id' => $kopiDingin,
                'supplies' => [
                    [$arabika->id, 0.025, 'Kg'],
                    [$es->id, 0.2, 'Kg'],
                    [$air->id, 0.15, 'L'],
                ]
            ],

            // Non-Kopi
            [
                'name' => 'Matcha Latte',
                'price' => 35000,
                'category_id' => $nonKopi,
                'supplies' => [
                    [$matcha->id, 0.01, 'Kg'],
                    [$susufull->id, 0.2, 'L'],
                    [$es->id, 0.15, 'Kg'],
                ]
            ],
            [
                'name' => 'Teh Tarik',
                'price' => 22000,
                'category_id' => $nonKopi,
                'supplies' => [
                    [$earlGrey->id, 0.005, 'Kg'],
                    [$susufull->id, 0.15, 'L'],
                    [$gula->id, 0.02, 'Kg'],
                ]
            ],
            [
                'name' => 'Coklat Panas',
                'price' => 28000,
                'category_id' => $nonKopi,
                'supplies' => [
                    [$coklat->id, 0.02, 'Kg'],
                    [$susufull->id, 0.2, 'L'],
                    [$gula->id, 0.02, 'Kg'],
                ]
            ],
            [
                'name' => 'Iced Matcha',
                'price' => 38000,
                'category_id' => $nonKopi,
                'supplies' => [
                    [$matcha->id, 0.012, 'Kg'],
                    [$susufull->id, 0.2, 'L'],
                    [$es->id, 0.15, 'Kg'],
                    [$gula->id, 0.015, 'Kg'],
                ]
            ],
            [
                'name' => 'Dark Chocolate',
                'price' => 32000,
                'category_id' => $nonKopi,
                'supplies' => [
                    [$darkChoc->id, 0.025, 'Kg'],
                    [$susufull->id, 0.2, 'L'],
                    [$whipped->id, 0.03, 'L'],
                ]
            ],

            // Minuman Segar
            [
                'name' => 'Es Teh Manis',
                'price' => 12000,
                'category_id' => $minuman,
                'supplies' => [
                    [$tehHijau->id, 0.005, 'Kg'],
                    [$gula->id, 0.02, 'Kg'],
                    [$es->id, 0.15, 'Kg'],
                    [$air->id, 0.3, 'L'],
                ]
            ],
            [
                'name' => 'Es Jeruk',
                'price' => 15000,
                'category_id' => $minuman,
                'supplies' => [
                    [$gula->id, 0.02, 'Kg'],
                    [$es->id, 0.15, 'Kg'],
                    [$air->id, 0.2, 'L'],
                ]
            ],
            [
                'name' => 'Vanilla Milk',
                'price' => 25000,
                'category_id' => $minuman,
                'supplies' => [
                    [$susufull->id, 0.25, 'L'],
                    [$vanilla->id, 0.02, 'L'],
                    [$es->id, 0.15, 'Kg'],
                ]
            ],

            // Makanan Ringan
            [
                'name' => 'Croissant',
                'price' => 22000,
                'category_id' => $makanan,
                'supplies' => [
                    [$tepung->id, 0.1, 'Kg'],
                    [$mentega->id, 0.05, 'Kg'],
                ]
            ],
            [
                'name' => 'Butter Toast',
                'price' => 18000,
                'category_id' => $makanan,
                'supplies' => [
                    [$tepung->id, 0.08, 'Kg'],
                    [$mentega->id, 0.03, 'Kg'],
                ]
            ],
        ];

        foreach ($products as $data) {
            $product = Product::create([
                'name' => $data['name'],
                'price' => $data['price'],
                'category_id' => $data['category_id'],
            ]);

            $syncData = [];
            foreach ($data['supplies'] as [$supplyId, $qty, $unit]) {
                $syncData[$supplyId] = ['qty' => $qty, 'unit' => $unit];
            }
            $product->supplies()->sync($syncData);
        }
    }
}
