@extends('layouts.app')

@section('title', $product->name . ' - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Detail Menu</h1>
        <a href="{{ route('cashier.products.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="max-w-2xl mx-auto space-y-4">

        {{-- Gambar kiri + Info kanan --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
            <div class="flex">
                {{-- Gambar --}}
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                        class="w-48 aspect-square object-cover shrink-0">
                @else
                    <div class="w-48 aspect-square bg-gray-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-utensils text-gray-600 text-3xl"></i>
                    </div>
                @endif

                {{-- Info --}}
                <div class="flex-1 p-5 space-y-3 text-sm">
                    <h2 class="text-base font-semibold text-white">{{ $product->name }}</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-2 border-b border-gray-700">
                            <span class="text-gray-400">Kategori</span>
                            <span class="px-2 py-0.5 bg-purple-500/10 text-purple-400 text-xs rounded-full">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-400">Harga</span>
                            <span class="text-white font-semibold text-base">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bahan Baku --}}
        @if($product->supplies->count() > 0)
            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-300">Bahan Baku</h2>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="text-left px-4 py-3 text-gray-400 font-medium">Nama Bahan</th>
                            <th class="text-left px-4 py-3 text-gray-400 font-medium">Qty</th>
                            <th class="text-left px-4 py-3 text-gray-400 font-medium">Satuan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($product->supplies as $supply)
                            <tr>
                                <td class="px-4 py-3 text-white">{{ $supply->name }}</td>
                                <td class="px-4 py-3 text-white">{{ $supply->pivot->qty }}</td>
                                <td class="px-4 py-3 text-gray-400">{{ $supply->pivot->unit }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
@endsection