<?php

namespace App\Http\Controllers\Cashier;
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
        $todayTransactions = Transaction::where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        $pendingCount = Transaction::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->count();

        $recentTransactions = Transaction::with('products')
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();

        return view('cashier.dashboard', compact(
            'todayTransaction',
            'pendingCount',
            'recentTransactions'
        ));
    }
}