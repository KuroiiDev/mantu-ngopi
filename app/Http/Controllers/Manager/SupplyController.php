<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Supply;
use App\Models\Product;
use App\Models\Restock;
use App\Models\PasswordResetRequest;
class SupplyController extends Controller
{
    public function index()
{
        $today = $this->todaySummary();
        $weeklySales = $this->weeklySales();
        $lowStocks = $this->lowStocks();
        $emptyStock = $this->emptyStock();
        $totalProduct = Product::count();
        $recentTransaction = Transaction::with('user')->latest()->limit(5)->get();
        $topProduct = $this->topProduct();
}