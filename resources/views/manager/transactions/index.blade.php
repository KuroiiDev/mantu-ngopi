@extends('layouts.app')

@section('title', 'Histori Transaksi - Mantu-Ngopi')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h1 class="text-xl font-bold text-white">Histori Transaksi</h1>
        
        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('manager.transactions.index') }}" method="GET" class="flex items-center gap-2">
                <input type="month" name="month" value="{{ request('month') }}" 
                    class="bg-gray-800 border border-gray-700 text-white text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block p-2">
                <button type="submit" class="p-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    <i class="fa-solid fa-filter"></i>
                </button>
                @if(request('month'))
                    <a href="{{ route('manager.transactions.index') }}" class="p-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>

            <a href="{{ route('manager.transactions.export', ['month' => request('month')]) }}" 
                id="btn-export-transactions"
                class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-purple-500/20">
                <i class="fa-solid fa-file-pdf"></i>
                Cetak Laporan PDF
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm rounded-lg">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">#</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Pelanggan</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Kasir</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Total</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Metode</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Status</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Tanggal</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-white">
                            {{ $transaction->customer ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-300">{{ $transaction->user->fullname }}</td>
                        <td class="px-4 py-3 text-white font-medium">
                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-gray-700 text-gray-300 text-xs rounded-full capitalize">
                                {{ $transaction->method ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
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
                        </td>
                        <td class="px-4 py-3 text-gray-400">
                            {{ $transaction->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('manager.transactions.show', $transaction) }}"
                                class="px-3 py-1.5 bg-purple-500/10 hover:bg-purple-500/20 text-purple-400 hover:text-purple-300 text-xs rounded-lg transition-colors">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-receipt mb-2 text-2xl block"></i>
                            Belum ada transaksi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection