<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;

use App\Models\Supply;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index()
    {
        $supplies = Supply::all();
        return view('manager.supplies.index', compact('supplies'));
    }

    public function create()
    {
        return view('manager.supplies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:supplies',
            'qty' => 'required|numeric|min:0',
            'unit' => 'required|in:Kg,L',
            'price' => 'required|numeric|min:0',
        ]);

        Supply::create($request->only('name', 'qty', 'unit', 'price'));

        return redirect()->route('manager.supplies.index')
            ->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    public function show(Supply $supply)
    {
        return redirect()->route('manager.supplies.edit', $supply);
    }

    public function edit(Supply $supply)
    {
        return view('manager.supplies.edit', compact('supply'));
    }

    public function update(Request $request, Supply $supply)
    {
        $request->validate([
            'name' => 'required|string|unique:supplies,name,' . $supply->id,
            'qty' => 'required|numeric|min:0',
            'unit' => 'required|in:Kg,L',
            'price' => 'required|numeric|min:0',
        ]);

        $supply->update($request->only('name', 'qty', 'unit', 'price'));

        return redirect()->route('manager.supplies.index')
            ->with('success', 'Bahan baku berhasil diupdate!');
    }

    public function destroy(Supply $supply)
    {
        $supply->delete();

        return redirect()->route('manager.supplies.index')
            ->with('success', 'Bahan baku berhasil dihapus!');
    }
}