@extends('layouts.app')

@section('title', 'Detail Bahan Baku - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Detail Bahan Baku</h1>
        <a href="{{ route('logistic.supplies.index') }}"
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

    <div class="max-w-2xl mx-auto space-y-4">

        {{-- Info Supply --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h2 class="text-sm font-semibold text-gray-300 mb-4">Informasi Bahan Baku</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Nama Bahan</span>
                    <span class="text-white font-medium">{{ $supply->name }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Stok Saat Ini</span>
                    <div class="flex items-center gap-2">
                        <span class="text-white font-medium">{{ $supply->qty }} {{ $supply->unit }}</span>
                        @if($supply->qty < 10)
                            <span class="px-2 py-0.5 bg-red-500/10 text-red-400 text-xs rounded-full">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i>Stok Rendah
                            </span>
                        @else
                            <span class="px-2 py-0.5 bg-green-500/10 text-green-400 text-xs rounded-full">
                                <i class="fa-solid fa-circle-check mr-1"></i>Aman
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-400">Harga per Satuan</span>
                    <span class="text-white">
                        Rp {{ number_format($supply->price, 0, ',', '.') }}/{{ $supply->unit }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Form Restock --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6" x-data="{
                    qty: 0,
                    pricePerUnit: {{ $supply->price }},
                    get totalPrice() {
                        return (parseFloat(this.qty) || 0) * this.pricePerUnit
                    },
                    formatRp(val) {
                        return new Intl.NumberFormat('id-ID').format(Math.round(val))
                    }
                }">
            <h2 class="text-sm font-semibold text-gray-300 mb-4">Input Restock</h2>

            <form action="{{ route('logistic.restocks.store', $supply) }}" method="POST" class="space-y-4">
                @csrf

                {{-- Qty --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">
                        Jumlah Restock ({{ $supply->unit }})
                    </label>
                    <input type="number" name="qty_added" x-model="qty" min="0.01" step="0.01"
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors"
                        placeholder="0">
                </div>

                {{-- Harga (readonly, live) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Total Harga</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input type="text" readonly :value="formatRp(totalPrice)"
                            class="w-full pl-9 pr-3 py-2 bg-gray-900/50 border border-gray-700 rounded-lg text-gray-400 text-sm cursor-not-allowed">
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Dihitung otomatis dari qty × Rp {{ number_format($supply->price, 0, ',', '.') }}/{{ $supply->unit }}
                    </p>
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fa-solid fa-boxes-stacking mr-2"></i>
                    Catat Restock
                </button>
            </form>
        </div>

        {{-- Histori Restock supply ini --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-700">
                <h2 class="text-sm font-semibold text-gray-300">Histori Restock</h2>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left px-4 py-3 text-gray-400 font-medium">Qty</th>
                        <th class="text-left px-4 py-3 text-gray-400 font-medium">Total Harga</th>
                        <th class="text-left px-4 py-3 text-gray-400 font-medium">Dicatat Oleh</th>
                        <th class="text-left px-4 py-3 text-gray-400 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($restocks as $restock)
                        <tr class="hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="text-white font-medium">+{{ $restock->qty_added }}</span>
                                <span class="text-gray-500 text-xs ml-1">{{ $supply->unit }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-300">
                                Rp {{ number_format($restock->price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-gray-300">{{ $restock->user->fullname }}</td>
                            <td class="px-4 py-3 text-gray-400">
                                {{ $restock->created_at->format('d M Y, H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                <i class="fa-solid fa-boxes-stacking mb-2 text-2xl block"></i>
                                Belum ada histori restock
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection