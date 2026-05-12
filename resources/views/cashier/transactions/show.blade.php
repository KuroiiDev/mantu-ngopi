@extends('layouts.app')

@section('title', 'Detail Transaksi - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Detail Transaksi</h1>
        <a href="{{ route('cashier.transactions.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm rounded-lg max-w-2xl mx-auto">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-lg max-w-2xl mx-auto">
            <i class="fa-solid fa-circle-exclamation mr-2"></i>
            <ul class="list-disc list-inside mt-1 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl mx-auto space-y-4">

        {{-- Info Transaksi --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-300">Informasi Transaksi</h2>
                @php
                    $statusColor = match ($transaction->status) {
                        'pending' => 'bg-yellow-500/10 text-yellow-400',
                        'paid' => 'bg-blue-500/10 text-blue-400',
                        'completed' => 'bg-green-500/10 text-green-400',
                        'cancelled' => 'bg-red-500/10 text-red-400',
                        default => 'bg-gray-500/10 text-gray-400',
                    };
                @endphp
                <span class="px-2 py-1 rounded-full text-xs font-medium capitalize {{ $statusColor }}">
                    {{ $transaction->status }}
                </span>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">ID Transaksi</span>
                    <span class="text-white font-medium">#{{ $transaction->id }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Pelanggan</span>
                    <span class="text-white">{{ $transaction->customer ?? 'Umum' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Kasir</span>
                    <span class="text-white">{{ $transaction->user->fullname }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Waktu</span>
                    <span class="text-white">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                </div>
                @if($transaction->method)
                    <div class="flex justify-between items-center py-2 border-b border-gray-700">
                        <span class="text-gray-400">Metode Bayar</span>
                        <span class="text-white capitalize">{{ $transaction->method }}</span>
                    </div>
                @endif
                @if($transaction->paid)
                    <div class="flex justify-between items-center py-2 border-b border-gray-700">
                        <span class="text-gray-400">Dibayar</span>
                        <span class="text-white">Rp {{ number_format($transaction->paid, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-400">Kembalian</span>
                        <span class="text-green-400 font-medium">
                            Rp {{ number_format($transaction->paid - $transaction->total, 0, ',', '.') }}
                        </span>
                    </div>
                @endif
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

            {{-- Total --}}
            <div class="mt-4 pt-4 border-t border-gray-700 flex justify-between items-center">
                <span class="text-sm font-semibold text-gray-300">Total</span>
                <span class="text-lg font-bold text-purple-400">
                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Action Buttons --}}
        @if($transaction->status === 'pending')
            {{-- Form Bayar --}}
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6" x-data="{
                            paid: {{ $transaction->total }},
                            total: {{ $transaction->total }},
                            get change() {
                                return (parseFloat(this.paid) || 0) - this.total
                            },
                            formatRp(val) {
                                return new Intl.NumberFormat('id-ID').format(Math.round(val))
                            }
                        }">
                <h2 class="text-sm font-semibold text-gray-300 mb-4">Proses Pembayaran</h2>
                <form action="{{ route('cashier.transactions.update', $transaction) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="pay">

                    {{-- Metode Bayar --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1.5">Metode Pembayaran</label>
                        <select name="payment_method"
                            class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:border-purple-500 transition-colors">
                            <option value="" disabled selected>Pilih metode</option>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    {{-- Nominal Bayar --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1.5">Nominal Bayar</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="paid" x-model="paid" min="{{ $transaction->total }}"
                                class="w-full pl-9 pr-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:border-purple-500 transition-colors">
                        </div>
                    </div>

                    {{-- Kembalian --}}
                    <div class="bg-gray-900 rounded-lg p-3 flex justify-between items-center text-sm">
                        <span class="text-gray-400">Kembalian</span>
                        <span class="font-semibold" :class="change >= 0 ? 'text-green-400' : 'text-red-400'"
                            x-text="'Rp ' + formatRp(change)">
                        </span>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fa-solid fa-money-bill mr-2"></i>
                        Proses Pembayaran
                    </button>
                </form>

                {{-- Batal --}}
                <form action="{{ route('cashier.transactions.update', $transaction) }}" method="POST" class="mt-3"
                    onsubmit="return confirm('Yakin batalkan pesanan ini?')">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="cancel">
                    <button type="submit"
                        class="w-full py-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 text-sm font-medium rounded-lg transition-colors">
                        <i class="fa-solid fa-xmark mr-2"></i>
                        Batalkan Pesanan
                    </button>
                </form>
            </div>

        @elseif($transaction->status === 'paid')
            {{-- Selesaikan Pesanan --}}
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <h2 class="text-sm font-semibold text-gray-300 mb-2">Selesaikan Pesanan</h2>
                <p class="text-xs text-gray-500 mb-4">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Tandai pesanan selesai setelah pesanan diserahkan ke pelanggan. Stok bahan baku akan berkurang otomatis.
                </p>
                <form action="{{ route('cashier.transactions.update', $transaction) }}" method="POST"
                    onsubmit="return confirm('Tandai pesanan ini sebagai selesai?')">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="complete">
                    <button type="submit"
                        class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fa-solid fa-circle-check mr-2"></i>
                        Tandai Selesai
                    </button>
                </form>
            </div>
        @endif

    </div>
@endsection