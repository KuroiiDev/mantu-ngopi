<?php

namespace App\Http\Controllers\Logistic;
use App\Http\Controllers\Controller;

use App\Models\Restock;
use App\Models\Supply;
use Illuminate\Http\Request;

class RestockController extends Controller
{
    public function index()
    {
        $restocks = Restock::with(['supply', 'user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('logistic.restock.index', compact('restocks'));
    }

    public function show(Restock $restock)
    {
        $restock->load(['supply', 'user']);
        return view('logistic.restocks.show', compact('restock'));
    }

    public function store(Request $request, Supply $supply)
    {
        $request->validate([
            'qty_added' => 'required|numeric|min:0.01',
        ]);

        $price = $request->qty_added * $supply->price;

        Restock::create([
            'supply_id' => $supply->id,
            'qty_added' => $request->qty_added,
            'price' => $price,
            'user_id' => auth()->id(),
        ]);

        $supply->increment('qty', $request->qty_added);

        return redirect()->route('logistic.supplies.show', $supply)
            ->with('success', 'Restock berhasil dicatat!');
    }
}
