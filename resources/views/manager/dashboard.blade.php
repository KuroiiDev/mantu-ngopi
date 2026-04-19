@extends('layouts.app')

@section('title', 'Dashboard - Mantu-Ngopi')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const weeklyData = @json($weeklySales);

        const labels = weeklyData.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });
        });

        const revenues = weeklyData.map(d => d.revenue);

        const ctx = document.getElementById('weeklyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: revenues,
                    backgroundColor: 'rgba(168, 85, 247, 0.5)',
                    borderColor: 'rgba(168, 85, 247, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw)
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    },
                    y: {
                        ticks: {
                            color: '#9ca3af',
                            callback: (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
                        },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    }
                }
            }
        });
    </script>
@endpush

@section('content')

    {{-- Metric Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Transaksi Hari Ini</span>
                <div class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-receipt text-purple-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $today['total_orders'] }}</p>
            <p class="text-xs text-gray-500 mt-1">transaksi</p>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Pendapatan Hari Ini</span>
                <div class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-money-bill text-green-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">
                Rp {{ number_format($today['total_revenue'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-500 mt-1">total pendapatan</p>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Total Menu</span>
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-utensils text-blue-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $totalProducts }}</p>
            <p class="text-xs text-gray-500 mt-1">menu aktif</p>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Hampir Habis</span>
                <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-orange-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $lowStocks->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">bahan baku</p>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400">Stok Habis</span>
                <div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark text-red-400 text-xs"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $emptyStocks->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">bahan baku</p>
        </div>
    </div>

    {{-- Chart --}}
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-300">Pendapatan 7 Hari Terakhir</h2>
        </div>
        <div class="h-56">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>

    {{-- Row 1: Transaksi Terbaru + Stok Rendah + Top Produk --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

        {{-- Transaksi Terbaru --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-300">Transaksi Terbaru</h2>
                <a href="{{ route('manager.transactions.index') }}"
                    class="text-xs text-purple-400 hover:text-purple-300 transition-colors">
                    Lihat Semua
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentTransactions as $transaction)
                    <a href="{{ route('manager.transactions.show', $transaction) }}"
                        class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0 hover:opacity-80 transition-opacity">
                        <div>
                            <p class="text-sm text-white">{{ $transaction->customer ?? 'Umum' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $transaction->user->fullname }} · {{ $transaction->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-white">
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </p>
                            @php
                                $statusColor = match ($transaction->status) {
                                    'paid' => 'text-green-400',
                                    'pending' => 'text-yellow-400',
                                    'cancelled' => 'text-red-400',
                                    default => 'text-gray-400',
                                };
                            @endphp
                            <p class="text-xs capitalize {{ $statusColor }}">{{ $transaction->status }}</p>
                        </div>
                    </a>
                @empty
                    <p class="text-center text-gray-500 text-sm py-4">Belum ada transaksi</p>
                @endforelse
            </div>
        </div>

        {{-- Stok Bermasalah --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-300">Perhatian Stok</h2>
                <a href="{{ route('manager.supplies.index') }}"
                    class="text-xs text-purple-400 hover:text-purple-300 transition-colors">
                    Lihat Semua
                </a>
            </div>
            <div class="space-y-2">
                {{-- Habis --}}
                @foreach($emptyStocks as $supply)
                    <div class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-red-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-circle-xmark text-red-400 text-xs"></i>
                            </div>
                            <p class="text-sm text-white">{{ $supply->name }}</p>
                        </div>
                        <span class="text-xs font-medium text-red-400">Habis</span>
                    </div>
                @endforeach

                {{-- Hampir Habis --}}
                @foreach($lowStocks as $supply)
                    <div class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-orange-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-triangle-exclamation text-orange-400 text-xs"></i>
                            </div>
                            <p class="text-sm text-white">{{ $supply->name }}</p>
                        </div>
                        <span class="text-xs font-medium text-orange-400">
                            {{ $supply->qty }} {{ $supply->unit }}
                        </span>
                    </div>
                @endforeach

                @if($emptyStocks->isEmpty() && $lowStocks->isEmpty())
                    <p class="text-center text-gray-500 text-sm py-4">
                        <i class="fa-solid fa-circle-check text-green-400 block text-xl mb-1"></i>
                        Semua stok aman
                    </p>
                @endif
            </div>
        </div>

        {{-- Top Produk --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-300">Menu Terlaris</h2>
                <a href="{{ route('manager.products.index') }}"
                    class="text-xs text-purple-400 hover:text-purple-300 transition-colors">
                    Lihat Semua
                </a>
            </div>
            <div class="space-y-3">
                @forelse($topProducts as $index => $product)
                    <div class="flex items-center gap-3 py-2 border-b border-gray-700 last:border-0">
                        <span class="text-xs font-bold text-gray-500 w-4">{{ $index + 1 }}</span>
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" class="w-7 h-7 rounded-lg object-cover">
                        @else
                            <div class="w-7 h-7 rounded-lg bg-gray-700 flex items-center justify-center">
                                <i class="fa-solid fa-utensils text-gray-500 text-xs"></i>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-white truncate">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="text-xs text-gray-400">
                            {{ $product->total_qty ?? 0 }}x
                        </span>
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-sm py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Row 2: Restock Terbaru + Password Requests --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Restock Terbaru --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-300">Restock Terbaru</h2>
                <a href="{{ route('manager.restocks.index') }}"
                    class="text-xs text-purple-400 hover:text-purple-300 transition-colors">
                    Lihat Semua
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentRestocks as $restock)
                    <div class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0">
                        <div>
                            <p class="text-sm text-white">{{ $restock->supply->name }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $restock->user->fullname }} · {{ $restock->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-white">
                                +{{ $restock->qty_added }} {{ $restock->supply->unit }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Rp {{ number_format($restock->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-sm py-4">Belum ada restock</p>
                @endforelse
            </div>
        </div>

        {{-- Password Requests --}}
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-300">Request Ganti Password</h2>
                @if($passwordRequests->count() > 0)
                    <span class="px-2 py-0.5 bg-yellow-500/10 text-yellow-400 text-xs rounded-full">
                        {{ $passwordRequests->count() }} pending
                    </span>
                @endif
            </div>
            <div class="space-y-3">
                @forelse($passwordRequests as $req)
                    <div class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-yellow-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-key text-yellow-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm text-white">{{ $req->user->fullname }}</p>
                                <p class="text-xs text-gray-500 capitalize">
                                    {{ $req->user->role }} · {{ $req->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('manager.password-reset-requests.update', $req) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit"
                                    class="px-2 py-1 bg-green-500/10 hover:bg-green-500/20 text-green-400 text-xs rounded-lg transition-colors">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('manager.password-reset-requests.update', $req) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit"
                                    class="px-2 py-1 bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs rounded-lg transition-colors">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-sm py-4">
                        <i class="fa-solid fa-circle-check text-green-400 block text-xl mb-1"></i>
                        Tidak ada request pending
                    </p>
                @endforelse
            </div>
        </div>

    </div>

@endsection