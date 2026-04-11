<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;

class TransactionController extends Controller 
{
    public function index()
{
    $transactions = Transaction::with(['user', 'products'])->latest()->get();
    return view('manager.transactions.index', compact('transactions'));
}
    public function show(Transaction $transaction)
{
    $transaction->load(['user', 'products']);
    return view('manager.transactions.show', compact('transaction'));
}
}