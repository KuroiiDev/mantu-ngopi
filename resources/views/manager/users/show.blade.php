@extends('layouts.app')

@section('title', 'Detail Karyawan - Mantu-Ngopi')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Detail Karyawan</h1>
        <a href="{{ route('manager.users.index') }}"
            class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm rounded-lg max-w-2xl mx-auto">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="max-w-2xl mx-auto space-y-4">

        {{-- Info User --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-300">Informasi Akun</h2>
                <a href="{{ route('manager.users.edit', $user) }}"
                    class="flex items-center gap-2 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-lg transition-colors">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit
                </a>
            </div>

            <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-700">
                <div class="w-14 h-14 rounded-full bg-purple-500/20 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user text-purple-400 text-xl"></i>
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

            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400">ID</span>
                    <span class="text-white">#{{ $user->id }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400">Bergabung</span>
                    <span class="text-white">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-400">Terakhir diupdate</span>
                    <span class="text-white">{{ $user->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        {{-- Password Reset Requests --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-300">Histori Request Ganti Password</h2>
                @php $pendingCount = $passwordRequests->where('status', 'pending')->count() @endphp
                @if($pendingCount > 0)
                    <span class="px-2 py-0.5 bg-yellow-500/10 text-yellow-400 text-xs rounded-full">
                        {{ $pendingCount }} pending
                    </span>
                @endif
            </div>

            <div class="space-y-3">
                @forelse($passwordRequests as $req)
                    <div class="flex items-center justify-between py-3 border-b border-gray-700 last:border-0">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                @php
                                    $reqColor = match ($req->status) {
                                        'pending' => 'bg-yellow-500/10 text-yellow-400',
                                        'approved' => 'bg-green-500/10 text-green-400',
                                        'rejected' => 'bg-red-500/10 text-red-400',
                                        default => 'bg-gray-500/10 text-gray-400',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium capitalize {{ $reqColor }}">
                                    {{ $req->status }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">{{ $req->created_at->format('d M Y, H:i') }}</p>
                        </div>

                        @if($req->status === 'pending')
                            <div class="flex items-center gap-2">
                                <form action="{{ route('manager.password-reset-requests.update', $req) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit"
                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 text-xs rounded-lg transition-colors">
                                        <i class="fa-solid fa-check"></i>
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('manager.password-reset-requests.update', $req) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit"
                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 text-xs rounded-lg transition-colors">
                                        <i class="fa-solid fa-xmark"></i>
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-xs text-gray-500">{{ $req->updated_at->diffForHumans() }}</span>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-sm py-4">
                        <i class="fa-solid fa-key block text-xl mb-1"></i>
                        Belum ada request ganti password
                    </p>
                @endforelse
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="bg-gray-800 rounded-xl border border-red-500/20 p-6">
            <h2 class="text-sm font-semibold text-red-400 mb-3">Hapus Akun</h2>
            <p class="text-xs text-gray-500 mb-4">
                Menghapus akun <span class="text-white font-medium">{{ $user->fullname }}</span> bersifat permanen dan tidak
                dapat dibatalkan.
            </p>
            <form action="{{ route('manager.users.destroy', $user) }}" method="POST"
                onsubmit="return confirm('Yakin hapus akun {{ $user->fullname }}?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 text-sm rounded-lg transition-colors">
                    <i class="fa-solid fa-trash"></i>
                    Hapus Akun
                </button>
            </form>
        </div>

    </div>
@endsection