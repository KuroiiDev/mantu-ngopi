@extends('layouts.app')

@section('title', 'Detail Restock - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Detail Restock</h1>
        <a href="{{ route('logistic.restocks.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="max-w-lg mx-auto space-y-4">

        {{-- Info Restock --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h2 class="text-sm font-semibold text-gray-300 mb-4">Informasi Restock</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">ID Restock</span>
                    <span class="text-white font-medium">#{{ $restock->id }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Tanggal</span>
                    <span class="text-white">{{ $restock->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-400">Dicatat Oleh</span>
                    <span class="text-white">{{ $restock->user->fullname }}</span>
                </div>
            </div>
        </div>

        {{-- Info Bahan Baku --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h2 class="text-sm font-semibold text-gray-300 mb-4">Detail Bahan Baku</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Nama Bahan</span>
                    <span class="text-white font-medium">{{ $restock->supply->name }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Jumlah Ditambahkan</span>
                    <span class="text-white">
                        +{{ $restock->qty_add }} {{ $restock->supply->unit }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-400">Harga per Satuan</span>
                    <span class="text-white">
                        Rp {{ number_format($restock->supply->price, 0, ',', '.') }}/{{ $restock->supply->unit }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-400">Stok Sekarang</span>
                    <span class="text-white font-medium">
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

    </div>
@endsection