@extends('layouts.app')

@section('title', 'Profil - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Profil Saya</h1>
    </div>

    @if(session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm rounded-lg max-w-lg mx-auto">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif

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

    <div class="max-w-lg mx-auto space-y-4">

        {{-- Avatar & Info --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-full bg-purple-500/20 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user text-purple-400 text-2xl"></i>
                </div>
                <div>
                    <p class="text-lg font-semibold text-white">{{ $user->fullname }}</p>
                    <p class="text-sm text-gray-400">{{ '@' . $user->username }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-medium capitalize
                            {{ match ($user->role) {
        'manager' => 'bg-purple-500/10 text-purple-400',
        'cashier' => 'bg-blue-500/10 text-blue-400',
        'logistic' => 'bg-green-500/10 text-green-400',
        default => 'bg-gray-500/10 text-gray-400',
    } }}">
                        {{ $user->role }}
                    </span>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Fullname --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}"
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors">
                </div>

                {{-- Username (readonly) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Username</label>
                    <input type="text" value="{{ $user->username }}" readonly
                        class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-lg text-gray-400 text-sm cursor-not-allowed">
                    <p class="text-xs text-gray-600 mt-1">Username tidak dapat diubah</p>
                </div>

                {{-- Role (readonly) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Role</label>
                    <input type="text" value="{{ ucfirst($user->role) }}" readonly
                        class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-lg text-gray-400 text-sm cursor-not-allowed capitalize">
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Link ganti password --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white">Password</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        @if(auth()->user()->role === 'manager')
                            Ubah password akun Anda
                        @else
                            Request pergantian password ke Manager
                        @endif
                    </p>
                </div>
                <a href="{{ route('change-password') }}"
                    class="flex items-center gap-2 px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-xs rounded-lg transition-colors">
                    <i class="fa-solid fa-lock"></i>
                    Ganti Password
                </a>
            </div>
        </div>

    </div>
@endsection