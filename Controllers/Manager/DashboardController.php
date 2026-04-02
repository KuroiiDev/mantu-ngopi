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
}

}