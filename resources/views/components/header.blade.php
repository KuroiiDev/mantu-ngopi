<header class="bg-gray-800 border-b border-gray-700 px-4 py-3 flex items-center justify-between">

    <!-- Hamburger mobile -->
    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-400 hover:text-white transition-colors">
        <i class="fa-solid fa-bars text-lg"></i>
    </button>

    <div class="hidden lg:block"></div>

    <!-- Right side -->
    <div class="flex items-center gap-3">

        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                class="flex items-center gap-2 text-sm text-gray-300 hover:text-white transition-colors">
                <div class="w-8 h-8 rounded-full bg-purple-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-user text-purple-400 text-xs"></i>
                </div>
                <span class="hidden sm:block font-medium">{{ auth()->user()->fullname }}</span>
                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>

            <!-- Dropdown -->
            <div x-show="open" x-cloak
                class="absolute right-0 mt-2 w-56 bg-gray-800 border border-gray-700 rounded-lg shadow-lg overflow-hidden">

                <!-- User Info -->
                <div class="px-4 py-3 border-b border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-user text-purple-400"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->fullname }}</p>
                            <p class="text-xs text-gray-400 truncate">{{'@'. auth()->user()->username }}</p>
                            <span
                                class="inline-block text-xs text-purple-400 bg-purple-500/10 px-2 py-0.5 rounded-full capitalize mt-0.5">
                                {{ auth()->user()->role }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Menu Items -->
                <ul class="py-1">
                    <li>
                        <a href="{{ route('profile') }}"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-gray-400 hover:bg-gray-700 hover:text-white transition-colors">
                            <i class="fa-solid fa-circle-user w-4 text-center"></i>
                            Profil Saya
                        </a>
                    </li>
                    {{-- <li>
                        <a href="{{ route('change-password') }}"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-gray-400 hover:bg-gray-700 hover:text-white transition-colors">
                            <i class="fa-solid fa-lock w-4 text-center"></i>
                            Ganti Password
                        </a>
                    </li> --}}
                </ul>

                <!-- Logout -->
                <div class="border-t border-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-gray-700 hover:text-red-300 transition-colors">
                            <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                            Sign out
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>

</header>