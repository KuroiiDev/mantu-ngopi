@extends('layouts.app')

@section('title', $product->name . ' - Mantu-Ngopi')

@section('content')

@endsection

<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-white">Detail Menu</h1>

    <a href="{{ route('cashier.products.index') }}"
        class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-colors">

        <i class="fa-solid fa-arrow-left"></i>
        Kembali
    </a>
</div>
<div class="max-w-2xl mx-auto space-y-4">
</div>
<div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
    <div class="flex">

    </div>
</div>
@if($product->image)
    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
        class="w-48 aspect-square object-cover shrink-0">
@else
    <div class="w-48 aspect-square bg-gray-700 flex items-center justify-center shrink-0">
        <i class="fa-solid fa-utensils text-gray-600 text-3xl"></i>
    </div>
@endif