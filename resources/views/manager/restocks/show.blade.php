@extends('layouts.app')

@section('title', 'Detail Restock - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Detail Restock</h1>
        <a href="{{ route('manager.restocks.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="max-w-lg mx-auto space-y-4">

        {{-- Info Restock --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-300">Informasi Restock</h2>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">ID Restock</span>
                    <span class="text-sm text-white font-medium">#{{ $restock->id }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">Tanggal</span>
                    <span class="text-sm text-white">{{ $restock->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">Dicatat Oleh</span>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-white">{{ $restock->user->fullname }}</span>
                        <span class="px-1.5 py-0.5 bg-gray-700 text-gray-400 text-xs rounded capitalize">
                            {{ $restock->user->role }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Bahan Baku --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-300">Detail Bahan Baku</h2>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">Nama Bahan</span>
                    <span class="text-sm text-white font-medium">{{ $restock->supply->name }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">Jumlah Ditambahkan</span>
                    <span class="text-sm text-white">
                        {{ $restock->qty_added }} {{ $restock->supply->unit }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">Harga per Satuan</span>
                    <span class="text-sm text-white">
                        Rp {{ number_format($restock->supply->price, 0, ',', '.') }}/{{ $restock->supply->unit }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-sm text-gray-400">Stok Sekarang</span>
                    <span class="text-sm font-medium text-white">
                        {{ $restock->supply->qty }} {{ $restock->supply->unit }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Total --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <div class="flex justify-between items-center">
                <span class="text-sm font-semibold text-gray-300">Total Harga Restock</span>
                <span class="text-lg font-bold text-purple-400">
                    Rp {{ number_format($restock->price, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Danger zone --}}
        <div class="bg-gray-800 rounded-xl border border-red-500/20 p-6">
            <h2 class="text-sm font-semibold text-red-400 mb-3">Hapus Data</h2>
            <p class="text-xs text-gray-500 mb-4">
                Menghapus data restock akan mengurangi stok bahan baku sebesar
                <span class="text-white font-medium">{{ $restock->qty_added }} {{ $restock->supply->unit }}</span>
                secara otomatis.
            </p>
            <form action="{{ route('manager.restocks.destroy', $restock) }}" method="POST"
                onsubmit="return confirm('Yakin hapus data restock ini? Stok akan berkurang otomatis!')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 text-sm rounded-lg transition-colors">
                    <i class="fa-solid fa-trash"></i>
                    Hapus Data Restock
                </button>
            </form>
        </div>

    </div>
@endsection