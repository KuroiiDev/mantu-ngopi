<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller 
{
    public function index()
{
    $transactions = Transaction::all();
    return view('manager.transactions.index', compact('transactions'));
}
}