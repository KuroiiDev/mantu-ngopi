@extends('layouts.app')

@section('title', 'Detail Transaksi - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Detail Transaksi</h1>
        <a href="{{ route('manager.transactions.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="max-w-lg mx-auto space-y-4">

        {{-- Info Transaksi --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-300">Informasi Transaksi</h2>
                @php
                    $statusColor = match ($transaction->status) {
                        'paid' => 'bg-green-500/10 text-green-400',
                        'pending' => 'bg-yellow-500/10 text-yellow-400',
                        'cancelled' => 'bg-red-500/10 text-red-400',
                        default => 'bg-gray-500/10 text-gray-400',
                    };
                @endphp
                <span class="px-2 py-1 rounded-full text-xs font-medium capitalize {{ $statusColor }}">
                    {{ $transaction->status }}
                </span>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">ID Transaksi</span>
                    <span class="text-sm text-white font-medium">#{{ $transaction->id }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">Tanggal</span>
                    <span class="text-sm text-white">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">Pelanggan</span>
                    <span class="text-sm text-white">{{ $transaction->customer ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">Kasir</span>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-white">{{ $transaction->user->fullname }}</span>
                        <span class="px-1.5 py-0.5 bg-gray-700 text-gray-400 text-xs rounded capitalize">
                            {{ $transaction->user->role }}
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">Metode Pembayaran</span>
                    <span class="text-sm text-white capitalize">{{ $transaction->method ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Item Pesanan --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h2 class="text-sm font-semibold text-gray-300 mb-4">Item Pesanan</h2>

            <div class="space-y-2">
                @foreach($transaction->products as $product)
                    <div class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0">
                        <div class="flex items-center gap-3">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="w-8 h-8 rounded-lg object-cover">
                            @else
                                <div class="w-8 h-8 rounded-lg bg-gray-700 flex items-center justify-center">
                                    <i class="fa-solid fa-utensils text-gray-500 text-xs"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm text-white">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $product->pivot->qty }}x @ Rp
                                    {{ number_format($product->pivot->price_at_transaction, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <span class="text-sm text-white font-medium">
                            Rp {{ number_format($product->pivot->qty * $product->pivot->price_at_transaction, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Ringkasan Pembayaran --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h2 class="text-sm font-semibold text-gray-300 mb-4">Ringkasan Pembayaran</h2>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-gray-400">
                    <span>Total</span>
                    <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-400">
                    <span>Dibayar</span>
                    <span>Rp {{ number_format($transaction->paid, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-white font-semibold border-t border-gray-700 pt-2">
                    <span>Kembalian</span>
                    <span class="text-green-400">
                        Rp {{ number_format($transaction->total - $transaction->paid, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

    </div>
@endsection