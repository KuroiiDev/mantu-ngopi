@extends('layouts.app')

@section('title', 'Tambah Karyawan - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Tambah Karyawan</h1>
        <a href="{{ route('manager.users.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 max-w-lg mx-auto">

        @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-lg">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                <ul class="list-disc list-inside mt-1 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('manager.users.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Fullname --}}
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Nama Lengkap</label>
                <input type="text" name="fullname" value="{{ old('fullname') }}"
                    class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors"
                    placeholder="Masukkan nama lengkap">
            </div>

            {{-- Username --}}
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Username</label>
                <input type="text" name="username" value="{{ old('username') }}"
                    class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors"
                    placeholder="Masukkan username">
            </div>

            {{-- Role --}}
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Role</label>
                <select name="role"
                    class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:border-purple-500 transition-colors">
                    <option value="" disabled selected>Pilih role</option>
                    <option value="manager"  {{ old('role') === 'manager'  ? 'selected' : '' }}>Manager</option>
                    <option value="cashier"  {{ old('role') === 'cashier'  ? 'selected' : '' }}>Cashier</option>
                    <option value="logistic" {{ old('role') === 'logistic' ? 'selected' : '' }}>Logistic</option>
                </select>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Password</label>
                <input type="password" name="password"
                    class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors"
                    placeholder="Minimal 8 karakter">
            </div>

            {{-- Password Confirmation --}}
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1.5">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors"
                    placeholder="Ulangi password">
            </div>

            <button type="submit"
                class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fa-solid fa-plus mr-2"></i>
                Tambah Karyawan
            </button>

        </form>
    </div>
@endsection