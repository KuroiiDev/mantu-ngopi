<?php

namespace App\Http\Controllers\Cashier;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supply;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products.supplies'])->get();

        // Pass supplies dengan qty & reserved ke Alpine
        $supplies = Supply::all(['id', 'qty', 'reserved']);

        return view('cashier.products.index', compact('categories', 'supplies'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplies']);
        return view('cashier.products.show', compact('product'));
    }
}