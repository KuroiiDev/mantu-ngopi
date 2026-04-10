<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Supply;
use App\Models\Product;
use App\Models\Restock;
use App\Models\PasswordResetRequest;
class DashboardController extends Controller
{
    public function index()
{
        $today = $this->todaySummary();
        $weeklySales = $this->weeklySales();
        $lowStocks = $this->lowStocks();
        $emptyStocks = $this->emptyStocks();
        $totalProducts = Product::count();
        $recentTransactions = Transaction::with('user')->latest()->limit(5)->get();
        $topProducts = $this->topProducts();
        $recentRestocks = Restock::with(['supply', 'user'])->latest()->limit(5)->get();
        $passwordRequests = PasswordResetRequest::with('user')
             ->where('status', 'pending')
            ->latest()
            ->get();

        return view('manager.dashboard', compact(
        'today',
        'weeklySales',
        'lowStocks',
        'emptyStocks',
        'totalProducts',
        'recentTransactions',
        'topProducts',
        'recentRestocks',
        'passwordRequests'
));
}
private function todaySummary()
{
    $transactions = Transaction::whereDate('created_at', today())
        ->where('status', 'paid')
        ->get();

    return [
        'total_orders' => $transactions->count(),
        'total_revenue' => $transactions->sum('total'),
    ];
}
private function weeklySales()
{
    return Transaction::selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as total_orders')
        ->where('status', 'paid')
        ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
        ->groupBy('date')
        ->orderBy('date')
        ->get();
}
private function topProducts()
{
    return Product::withSum('transactions as total_qty', 'product_transaction_r.qty')
        ->orderByDesc('total_qty')
        ->limit(5)
        ->get(['id', 'name', 'price']);
}
private function lowStocks()
{
    return Supply::where('qty', '>', 0)
        ->where('qty', '<', 10)
        ->get(['id', 'name', 'qty', 'unit']);
}
}