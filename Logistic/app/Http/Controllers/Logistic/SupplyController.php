<?php

namespace App\Http\Controllers\Logistic;
use App\Http\Controllers\Controller;

use App\Models\Supply;

class SupplyController extends Controller
{
    public function index()
    {
        $supplies = Supply::all();
        return view('logistic.supplies.index', compact('supplies'));
    }

    public function show(Supply $supply)
    {
        $restocks = $supply->restocks()->with('user')->latest()->get();
        return view('logistic.supplies.show', compact('supply', 'restocks'));
    }
}