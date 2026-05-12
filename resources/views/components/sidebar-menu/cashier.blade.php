@php
    $menuItems = [
        ['label' => 'Dashboard', 'icon' => 'fa-gauge', 'route' => route('cashier.dashboard'), 'active' => request()->is('cashier')],
        ['label' => 'Pemesanan', 'icon' => 'fa-utensils', 'route' => route('cashier.products.index'), 'active' => request()->is('cashier/products*')],
        ['label' => 'Transaksi', 'icon' => 'fa-receipt', 'route' => route('cashier.transactions.index'), 'active' => request()->is('cashier/transactions*')],
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