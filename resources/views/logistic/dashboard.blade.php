@extends('layouts.app')

@section('title', 'Dashboard Logistik - Mantu-Ngopi')

@section('content')

    {{-- Metric Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Total Bahan Baku</span>
                <div class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-box text-purple-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $totalSupplies }}</p>
            <p class="text-xs text-gray-500 mt-1">jenis bahan</p>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Hampir Habis</span>
                <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-orange-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $lowStocks->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">bahan baku</p>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Stok Habis</span>
                <div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark text-red-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $emptyStocks->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">bahan baku</p>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Restock Hari Ini</span>
                <div class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-boxes-stacking text-green-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">
                {{ $recentRestocks->filter(fn($r) => $r->created_at->isToday())->count() }}
            </p>
            <p class="text-xs text-gray-500 mt-1">kali restock</p>
        </div>
    </div>

    {{-- Row: Stok Bermasalah + Restock Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Stok Bermasalah --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-300">Perhatian Stok</h2>
                <a href="{{ route('logistic.supplies.index') }}"
                    class="text-xs text-purple-400 hover:text-purple-300 transition-colors">
                    Lihat Semua
                </a>
            </div>

            {{-- Habis --}}
            @forelse($emptyStocks as $supply)
                <a href="{{ route('logistic.supplies.show', $supply) }}"
                    class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0 hover:opacity-80 transition-opacity">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-red-500/10 flex items-center justify-center">
                            <i class="fa-solid fa-circle-xmark text-red-400 text-xs"></i>
                        </div>
                        <p class="text-sm text-white">{{ $supply->name }}</p>
                    </div>
                    <span class="text-xs font-medium text-red-400">Habis</span>
                </a>
            @empty
            @endforelse

            {{-- Hampir Habis --}}
            @forelse($lowStocks as $supply)
                <a href="{{ route('logistic.supplies.show', $supply) }}"
                    class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0 hover:opacity-80 transition-opacity">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-orange-500/10 flex items-center justify-center">
                            <i class="fa-solid fa-triangle-exclamation text-orange-400 text-xs"></i>
                        </div>
                        <p class="text-sm text-white">{{ $supply->name }}</p>
                    </div>
                    <span class="text-xs font-medium text-orange-400">
                        {{ $supply->qty }} {{ $supply->unit }}
                    </span>
                </a>
            @empty
            @endforelse

            @if($emptyStocks->isEmpty() && $lowStocks->isEmpty())
                <p class="text-center text-gray-500 text-sm py-4">
                    <i class="fa-solid fa-circle-check text-green-400 block text-xl mb-1"></i>
                    Semua stok aman
                </p>
            @endif
        </div>

        {{-- Restock Terbaru --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-300">Restock Terbaru</h2>
                <a href="{{ route('logistic.restocks.index') }}"
                    class="text-xs text-purple-400 hover:text-purple-300 transition-colors">
                    Lihat Semua
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentRestocks as $restock)
                    <a href="{{ route('logistic.restocks.show', $restock) }}"
                        class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0 hover:opacity-80 transition-opacity">
                        <div>
                            <p class="text-sm text-white">{{ $restock->supply->name }}</p>
                            <p class="text-xs text-gray-500">{{ $restock->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-green-400">+{{ $restock->qty_added }}
                                {{ $restock->supply->unit }}</p>
                            <p class="text-xs text-gray-500">Rp {{ number_format($restock->price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @empty
                    <p class="text-center text-gray-500 text-sm py-4">
                        <i class="fa-solid fa-boxes-stacking block text-xl mb-1"></i>
                        Belum ada restock
                    </p>
                @endforelse
            </div>
        </div>

    </div>

@endsection