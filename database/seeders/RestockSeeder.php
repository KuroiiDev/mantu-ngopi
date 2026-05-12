<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Restock;
use App\Models\Supply;
use App\Models\User;

class RestockSeeder extends Seeder
{
    public function run(): void
    {
        $logistik = User::where('role', 'logistic')->first();
        $supplies = Supply::all();

        $restocks = [
            ['Biji Kopi Arabika', 3, -7],
            ['Biji Kopi Robusta', 5, -14],
            ['Susu Full Cream',   8, -3],
            ['Gula Pasir',        10, -5],
            ['Es Batu',           20, -1],
            ['Matcha Powder',     1, -10],
            ['Bubuk Coklat',      2, -6],
        ];

        foreach ($restocks as [$name, $qty, $daysAgo]) {
            $supply = $supplies->where('name', $name)->first();
            if (!$supply) continue;

            Restock::create([
                'supply_id' => $supply->id,
                'user_id'   => $logistik->id,
                'qty_added' => $qty,
                'price'     => $qty * $supply->price,
                'created_at' => now()->addDays($daysAgo),
                'updated_at' => now()->addDays($daysAgo),
            ]);
        }
    }
}
