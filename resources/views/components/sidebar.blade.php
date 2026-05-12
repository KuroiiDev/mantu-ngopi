<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-800 border-r border-gray-700 flex flex-col transition-transform duration-300 lg:static lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-3.5 border-b border-gray-700">
        <i class="fa-solid fa-mug-hot text-purple-400 text-xl"></i>
        <span class="font-bold text-white text-lg">Mantu-Ngopi</span>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 overflow-y-auto px-4 py-4">
        @if(auth()->user()->role === 'manager')
            @include('components.sidebar-menu.manager')
        @elseif(auth()->user()->role === 'cashier')
            @include('components.sidebar-menu.cashier')
        @elseif(auth()->user()->role === 'logistic')
            @include('components.sidebar-menu.logistic')
        @endif
    </nav>

    {{-- User info bottom --}}
    <div class="px-4 py-4 border-t border-gray-700">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-purple-500/20 flex items-center justify-center">
                <i class="fa-solid fa-user text-purple-400 text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->fullname }}</p>
                <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
            </div>
        </div>
    </div>

</aside>