<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Supply;
use App\Models\Product;
use App\Models\Restock;
use App\Models\PasswordResetRequest;
class RestockController extends Controller
{
    public function index()
{
        $today = $this->todaySummary();
        $weeklySale = $this->weeklySale();
        $lowStock = $this->lowStock();
        $emptyStock = $this->emptyStock();
        $totalProducts = Products::count();
        $recentTransaction = Transaction::with('user')->latest()->limit(5)->get();
        $topProduct = $this->topProduct();
}