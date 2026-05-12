<?php

namespace App\Http\Controllers\Cashier;
use App\Http\Controllers\Controller;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Supply;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('products')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('cashier.transactions.index', compact('transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.qty' => 'required|numeric|min:1',
        ]);

        // Validasi stok efektif
        $errors = [];
        foreach ($request->products as $item) {
            $product = Product::with('supplies')->find($item['product_id']);
            foreach ($product->supplies as $supply) {
                $needed = $supply->pivot->qty * $item['qty'];
                if (!$supply->isAvailableFor($needed)) {
                    $effective = $supply->effectiveQty();
                    $maxQty = $supply->pivot->qty > 0
                        ? floor($effective / $supply->pivot->qty)
                        : 0;
                    $errors[] = "Stok {$supply->name} tidak mencukupi untuk {$item['qty']}x {$product->name}. Maksimal {$maxQty}x.";
                }
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $total = collect($request->products)->sum(function ($item) {
            $product = Product::find($item['product_id']);
            return $product->price * $item['qty'];
        });

        $transaction = Transaction::create([
            'customer' => $request->customer,
            'total' => $total,
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]);

        $transaction->products()->sync(
            collect($request->products)->mapWithKeys(function ($item) {
                $product = Product::find($item['product_id']);
                return [
                    $item['product_id'] => [
                        'qty' => $item['qty'],
                        'price_at_transaction' => $product->price,
                    ]
                ];
            })->toArray()
        );

        // Tambah reserved
        $this->updateReserved($transaction, 'add');

        return redirect()->route('cashier.transactions.show', $transaction)
            ->with('success', 'Pesanan berhasil dibuat!');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['products', 'user']);
        return view('cashier.transactions.show', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'action' => 'required|in:pay,complete,cancel',
            'payment_method' => 'required_if:action,pay|nullable|string',
            'paid' => 'required_if:action,pay|nullable|numeric|min:0',
        ]);

        switch ($request->action) {
            case 'pay':
                if ($request->paid < $transaction->total) {
                    return back()->withErrors(['paid' => 'Nominal bayar kurang dari total!']);
                }
                $transaction->update([
                    'status' => 'paid',
                    'method' => $request->payment_method,
                    'paid' => $request->paid,
                ]);
                // reserved tetap, masih "dipesan"
                break;

            case 'complete':
                // Kurangi stok beneran & lepas reserved
                foreach ($transaction->products as $product) {
                    $product->load('supplies');
                    foreach ($product->supplies as $supply) {
                        $needed = $supply->pivot->qty * $product->pivot->qty;
                        $supply->decrement('qty', $needed);
                        $supply->decrement('reserved', $needed);
                    }
                }
                $transaction->update(['status' => 'completed']);
                break;

            case 'cancel':
                // Lepas reserved
                $this->updateReserved($transaction, 'remove');
                $transaction->update(['status' => 'cancelled']);
                break;
        }

        return redirect()->route('cashier.transactions.show', $transaction)
            ->with('success', 'Status pesanan berhasil diupdate!');
    }

    // Helper: tambah atau kurangi reserved
    private function updateReserved(Transaction $transaction, string $action): void
    {
        $transaction->load('products.supplies');

        foreach ($transaction->products as $product) {
            foreach ($product->supplies as $supply) {
                $needed = $supply->pivot->qty * $product->pivot->qty;
                if ($action === 'add') {
                    $supply->increment('reserved', $needed);
                } else {
                    $supply->decrement('reserved', $needed);
                }
            }
        }
    }
}