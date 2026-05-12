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