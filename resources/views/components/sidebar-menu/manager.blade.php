@php
    $menuItems = [
        ['label' => 'Dashboard', 'icon' => 'fa-gauge', 'route' => url('/manager'), 'active' => request()->is('manager')],
        ['label' => 'Kategori', 'icon' => 'fa-layer-group', 'route' => route('manager.categories.index'), 'active' => request()->is('manager/categories*')],
        ['label' => 'Bahan Baku', 'icon' => 'fa-box', 'route' => route('manager.supplies.index'), 'active' => request()->is('manager/supplies*')],
        ['label' => 'Menu & Harga', 'icon' => 'fa-utensils', 'route' => route('manager.products.index'), 'active' => request()->is('manager/products*')],
        ['label' => 'Transaksi', 'icon' => 'fa-receipt', 'route' => route('manager.transactions.index'), 'active' => request()->is('manager/transactions*')],
        ['label' => 'Restock', 'icon' => 'fa-boxes-stacked', 'route' => route('manager.restocks.index'), 'active' => request()->is('manager/restocks*')],
        ['label' => 'Karyawan', 'icon' => 'fa-users', 'route' => route('manager.users.index'), 'active' => request()->is('manager/users*')],
    ];
@endphp

<ul class="space-y-1">
    @foreach($menuItems as $item)
        <li>
            <a href="{{ $item['route'] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                                {{ $item['active']
            ? 'bg-purple-500/20 text-purple-400'
            : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                <i class="fa-solid {{ $item['icon'] }} w-4 text-center"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        </li>
    @endforeach
</ul>