@extends('layouts.app')

@section('title', 'Manajemen Karyawan - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Manajemen Karyawan</h1>
        <a href="{{ route('manager.users.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-plus"></i>
            Tambah Karyawan
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm rounded-lg">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">#</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Nama Lengkap</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Username</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Role</th>
                    <th class="text-left px-4 py-3 text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-white">{{ $user->fullname }}</td>
                        <td class="px-4 py-3 text-gray-300">{{ '@' . $user->username }}</td>
                        <td class="px-4 py-3">
                            @php
                                $roleColor = match ($user->role) {
                                    'manager' => 'bg-purple-500/10 text-purple-400',
                                    'cashier' => 'bg-blue-500/10 text-blue-400',
                                    'logistic' => 'bg-green-500/10 text-green-400',
                                    default => 'bg-gray-500/10 text-gray-400',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium capitalize {{ $roleColor }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('manager.users.show', $user) }}"
                                    class="px-3 py-1.5 bg-purple-500/10 hover:bg-purple-500/20 text-purple-400 hover:text-purple-300 text-xs rounded-lg transition-colors">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                {{-- <a href="{{ route('manager.users.edit', $user) }}"
                                    class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-xs rounded-lg transition-colors">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('manager.users.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Hapus akun {{ $user->fullname }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 text-xs rounded-lg transition-colors">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form> --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-users-slash mb-2 text-2xl block"></i>
                            Belum ada karyawan terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection