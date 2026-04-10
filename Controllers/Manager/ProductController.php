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
        'name' => 'required|string|unique:products',
        'price' => 'required|numeric',
        'image' => 'nullable|image|max:2048',
        'category_id' => 'required|exists:categories,id',
        'supplies' => 'nullable|array',
        'supplies.*.supply_id' => 'required|exists:supplies,id',
        'supplies.*.qty' => 'required|numeric|min:0',
        'supplies.*.unit' => 'required|in:Kg,L',
    ]);
if ($request->hasFile('image')) {
    $validated['image'] = $request->file('image')->store('products', 'public');
}

$product = Product::create($validated);

if (!empty($validated['supplies'])) {
    $product->supplies()->sync(
        collect($validated['supplies'])->mapWithKeys(fn($s) => [
            $s['supply_id'] => ['qty' => $s['qty'], 'unit' => $s['unit']]
        ])->toArray()
    );
}
return redirect()->route('manager.products.index')
    ->with('success', 'Menu berhasil ditambahkan!');
}
public function show(Product $product)
{
    return redirect()->route('manager.products.edit', $product);
}

public function edit(Product $product)
{
    $categories = Category::all();
    $supplies = Supply::select('id', 'name', 'price', 'unit')->get();
    $product->load('supplies');
    return view('manager.products.edit', compact('product', 'categories', 'supplies'));
}
}