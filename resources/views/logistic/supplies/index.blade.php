@extends('layouts.app')

@section('title', 'Bahan Baku - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Bahan Baku</h1>
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">#</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Nama Bahan</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Stok</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Harga/Satuan</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Status</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($supplies as $supply)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-white">{{ $supply->name }}</td>
                        <td class="px-4 py-3">
                            <span class="text-white font-medium">{{ $supply->qty }}</span>
                            <span class="text-gray-500 text-xs ml-1">{{ $supply->unit }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            Rp {{ number_format($supply->price, 0, ',', '.') }}/{{ $supply->unit }}
                        </td>
                        <td class="px-4 py-3">
                            @php $status = $supply->stockStatus() @endphp
                            @if($status === 'habis')
                                <span class="px-2 py-1 bg-red-500/10 text-red-400 text-xs rounded-full">
                                    <i class="fa-solid fa-circle-xmark mr-1"></i>Habis
                                </span>
                            @elseif($status === 'tipis')
                                <span class="px-2 py-1 bg-orange-500/10 text-orange-400 text-xs rounded-full">
                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>Hampir Habis
                                </span>
                            @else
                                <span class="px-2 py-1 bg-green-500/10 text-green-400 text-xs rounded-full">
                                    <i class="fa-solid fa-circle-check mr-1"></i>Aman
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('logistic.supplies.show', $supply) }}"
                                class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-xs rounded-lg transition-colors">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-box mb-2 text-2xl block"></i>
                            Belum ada bahan baku
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection