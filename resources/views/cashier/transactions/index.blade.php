@extends('layouts.app')

@section('title', 'Transaksi - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Transaksi Saya</h1>
        <a href="{{ route('cashier.products.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-plus"></i>
            Buat Pesanan
        </a>
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">#</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Pelanggan</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Total</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Status</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Waktu</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
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
                            {{ $transaction->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-3 flex items-center gap-2">
                            <a href="{{ route('cashier.transactions.show', $transaction) }}"
                                class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-xs rounded-lg transition-colors">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($transaction->status === 'paid')
                                <form action="{{ route('cashier.transactions.update', $transaction) }}" method="POST"
                                    onsubmit="return confirm('Tandai pesanan ini sebagai selesai?')">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="complete">
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-gray-700 hover:bg-green-700 text-white text-xs rounded-lg transition-colors">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-receipt mb-2 text-2xl block"></i>
                            Belum ada transaksi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection