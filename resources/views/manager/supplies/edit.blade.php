@extends('layouts.app')

@section('title', 'Edit Bahan Baku - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Edit Bahan Baku</h1>
        <a href="{{ route('manager.supplies.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-lg max-w-lg mx-auto">
            <i class="fa-solid fa-circle-exclamation mr-2"></i>
            <ul class="list-disc list-inside mt-1 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 max-w-lg mx-auto">
        <form action="{{ route('manager.supplies.update', $supply) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Nama Bahan Baku</label>
                <input type="text" name="name" value="{{ old('name', $supply->name) }}"
                    class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Stok</label>
                    <input type="number" name="qty" value="{{ old('qty', $supply->qty) }}" min="0" step="0.01"
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Satuan</label>
                    <select name="unit"
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:border-purple-500 transition-colors">
                        <option value="Kg" {{ old('unit', $supply->unit) === 'Kg' ? 'selected' : '' }}>Kg</option>
                        <option value="L" {{ old('unit', $supply->unit) === 'L' ? 'selected' : '' }}>L</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Harga per Satuan</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                    <input type="number" name="price" value="{{ old('price', $supply->price) }}" min="0"
                        class="w-full pl-9 pr-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors">
                </div>
                <p class="text-xs text-gray-500 mt-1.5">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Perubahan harga akan mempengaruhi kalkulasi harga menu
                </p>
            </div>

            <button type="submit"
                class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fa-solid fa-floppy-disk mr-2"></i>
                Simpan Perubahan
            </button>

        </form>
    </div>
@endsection