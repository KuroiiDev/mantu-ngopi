@php
    $menuItems = [
        ['label' => 'Dashboard', 'icon' => 'fa-gauge', 'route' => route('logistic.dashboard'), 'active' => request()->is('logistic')],
        ['label' => 'Bahan Baku', 'icon' => 'fa-box', 'route' => route('logistic.supplies.index'), 'active' => request()->is('logistic/supplies*')],
        ['label' => 'Restock', 'icon' => 'fa-boxes-stacked', 'route' => route('logistic.restocks.index'), 'active' => request()->is('logistic/restocks*')],
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