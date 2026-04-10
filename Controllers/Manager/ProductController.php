<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

 use App\Models\Product;
use App\Models\Category;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
public function index()
{
    $products = Product::with(['category', 'supplies'])->get();
    return view('manager.products.index', compact('products'));
}

public function create()
{
    $categories = Category::all();
    $supplies = Supply::select('id', 'name', 'price', 'unit')->get();
    return view('manager.products.create', compact('categories', 'supplies'));
}
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'requred|string|unique:products',
        'price' => 'required|numeric',
        'image' => 'nullable|image|max:2048',
        'category_id' => 'required|exists:categories,id',
        'supplies' => 'nullable|array',
        'supplies.*.supply_id' => 'required|exists:supplies,id',
        'supplies.*.qty' => 'required|numeric|min:0',
        'supplies.*.unit' => 'required|in:Kg,L',
    ]);
}
}