@extends('layouts.app')

@section('title', 'Kategori - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Kategori</h1>
        <a href="{{ route('manager.categories.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-plus"></i>
            Tambah Kategori
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
        <th class="text-left px-4 py-3 text-gray-400 font-medium">Nama Kategori</th>
        <th class="text-left px-4 py-3 text-gray-400 font-medium">Jumlah Menu</th>
        <th class="text-left px-4 py-3 text-gray-400 font-medium">Aksi</th>
    </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-white">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ $category->products_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('manager.categories.edit', $category) }}"
                                    class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-xs rounded-lg transition-colors">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('manager.categories.destroy', $category) }}" method="POST"
                                    onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
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
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-layer-group mb-2 text-2xl block"></i>
                            Belum ada kategori
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection