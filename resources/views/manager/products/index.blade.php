@extends('layouts.app')

@section('title', 'Menu & Harga - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Menu & Harga</h1>
        <a href="{{ route('manager.products.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-plus"></i>
            Tambah Menu
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
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Gambar</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Nama Menu</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Kategori</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Harga</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Status</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                    class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-gray-700 flex items-center justify-center">
                                    <i class="fa-solid fa-utensils text-gray-500 text-xs"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-white">{{ $product->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-purple-500/10 text-purple-400 text-xs rounded-full">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            @php $status = $product->productStatus() @endphp
                            @if($status === 'habis')
                                <span class="px-2 py-1 bg-red-500/10 text-red-400 text-xs rounded-full">
                                    <i class="fa-solid fa-circle-xmark mr-1"></i>Stok Habis
                                </span>
                            @elseif($status === 'tipis')
                                <span class="px-2 py-1 bg-orange-500/10 text-orange-400 text-xs rounded-full">
                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>Stok Tipis
                                </span>
                            @else
                                <span class="px-2 py-1 bg-green-500/10 text-green-400 text-xs rounded-full">
                                    <i class="fa-solid fa-circle-check mr-1"></i>Tersedia
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('manager.products.edit', $product) }}"
                                    class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-xs rounded-lg transition-colors">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('manager.products.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('Hapus menu {{ $product->name }}?')">
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
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-utensils mb-2 text-2xl block"></i>
                            Belum ada menu terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection