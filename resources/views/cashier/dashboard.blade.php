@extends('layouts.app')

@section('title', 'Dashboard - Mantu-Ngopi')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Dashboard</h1>
        <a href="{{ route('cashier.products.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-plus mr-1"></i>
            Buat Pesanan
        </a>
    </div>

    {{-- Metric Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Transaksi Hari Ini</span>
                <div class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-receipt text-purple-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $todayTransactions->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">transaksi</p>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Menunggu Proses</span>
                <div class="w-8 h-8 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-yellow-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $pendingCount }}</p>
            <p class="text-xs text-gray-500 mt-1">pesanan pending</p>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4 col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Selesai Hari Ini</span>
                <div class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-green-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">
                {{ $todayTransactions->where('status', 'completed')->count() }}
            </p>
            <p class="text-xs text-gray-500 mt-1">pesanan selesai</p>
        </div>
    </div>

    {{-- Transaksi Terbaru --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">
            <h2 class="text-sm font-semibold text-gray-300">Transaksi Terbaru</h2>
            <a href="{{ route('cashier.transactions.index') }}"
                class="text-xs text-purple-400 hover:text-purple-300 transition-colors">
                Lihat Semua
            </a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Pelanggan</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Total</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Status</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Waktu</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($recentTransactions as $transaction)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-white">{{ $transaction->customer ?? 'Umum' }}</td>
                        <td class="px-4 py-3 text-white font-medium">
                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
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
                        </td>
                        <td class="px-4 py-3 text-gray-400">
                            {{ $transaction->created_at->diffForHumans() }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('cashier.transactions.show', $transaction) }}"
                                class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-xs rounded-lg transition-colors">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-receipt mb-2 text-2xl block"></i>
                            Belum ada transaksi hari ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection