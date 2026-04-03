@extends('layouts.app')

@section('title', 'Histori Restock - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Histori Restock</h1>
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
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Bahan Baku</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Qty</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Total Harga</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Dicatat Oleh</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Tanggal</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($restocks as $restock)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-white">{{ $restock->supply->name }}</td>
                        <td class="px-4 py-3">
                            <span class="text-white font-medium">{{ $restock->qty_added }}</span>
                            <span class="text-gray-500 text-xs ml-1">{{ $restock->supply->unit }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            Rp {{ number_format($restock->price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-purple-500/20 flex items-center justify-center">
                                    <i class="fa-solid fa-user text-purple-400 text-xs"></i>
                                </div>
                                <span class="text-gray-300">{{ $restock->user->fullname }}</span>
                                <span class="px-1.5 py-0.5 bg-gray-700 text-gray-400 text-xs rounded capitalize">
                                    {{ $restock->user->role }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-400">
                            {{ $restock->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('manager.restocks.show', $restock) }}"
                                class="px-3 py-1.5 bg-purple-500/10 hover:bg-purple-500/20 text-purple-400 hover:text-purple-300 text-xs rounded-lg transition-colors">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-boxes-stacking mb-2 text-2xl block"></i>
                            Belum ada histori restock
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection