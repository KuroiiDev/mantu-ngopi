<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $cashier = User::where('role', 'cashier')->first();
        $products = Product::all();

        $customers = ['Budi', 'Siti', 'Andi', 'Rini', 'Dian', 'Fajar', null, null];
        $methods   = ['cash', 'transfer', 'qris'];
        $statuses  = ['completed', 'completed', 'completed', 'paid', 'cancelled'];

        for ($i = 0; $i < 30; $i++) {
            $status   = $statuses[array_rand($statuses)];
            $method   = $status !== 'pending' && $status !== 'cancelled'
                        ? $methods[array_rand($methods)] : null;

            // Pilih 1-3 produk random
            $selectedProducts = $products->random(rand(1, 3));

            $total = 0;
            $productSync = [];
            foreach ($selectedProducts as $product) {
                $qty = rand(1, 3);
                $total += $product->price * $qty;
                $productSync[$product->id] = [
                    'qty'                  => $qty,
                    'price_at_transaction' => $product->price,
                ];
            }

            $paid = $method ? $total + rand(0, 5) * 1000 : null;

            $transaction = Transaction::create([
                'customer'   => $customers[array_rand($customers)],
                'status'     => $status,
                'method'     => $method,
                'paid'       => $paid,
                'total'      => $total,
                'user_id'    => $cashier->id,
                'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 8)),
                'updated_at' => now()->subDays(rand(0, 30)),
            ]);

            $transaction->products()->sync($productSync);
        }
    }
}
