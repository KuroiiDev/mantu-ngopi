@extends('layouts.app')

@section('title', 'Bahan Baku - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Bahan Baku</h1>
        <a href="{{ route('manager.supplies.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-plus"></i>
            Tambah Bahan Baku
        </a>
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
                            <div class="flex items-center gap-2">
                                <a href="{{ route('manager.supplies.edit', $supply) }}"
                                    class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-xs rounded-lg transition-colors">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('manager.supplies.destroy', $supply) }}" method="POST"
                                    onsubmit="return confirm('Hapus bahan baku {{ $supply->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 text-xs rounded-lg transition-colors">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-box mb-2 text-2xl block"></i>
                            Belum ada bahan baku terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection