<?php

namespace App\Http\Controllers\Logistic;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Supply;
use App\Models\Restock;

class DashboardController extends Controller
{
    public function index()
    {
        $lowStocks = $this->lowStocks();
        $emptyStock = $this->emptyStock();
        $totalSupplies = Supply::count();
        $recentRestocks = Restock::with(['supply', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('logistic.dashboard', compact(
            'lowStocks',
            'emptyStocks',
            'totalSupplies',
            'recentRestocks'
        ));
    }

    private function lowStocks()
    {
        return Supply::where('qty', '>', 0)
            ->where('qty', '<', 10)
            ->get(['id', 'name', 'qty', 'unit']);
    }

    private function emptyStocks()
    {
        return Supply::where('qty', '<=', 0)
            ->get(['id', 'name', 'qty', 'unit']);
    }
}