<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Mantu-Ngopi')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/a740d9e852.js" crossorigin="anonymous"></script>
    @stack('styles')
</head>

<body x-data="{ sidebarOpen: false }" class="bg-gray-900 text-gray-100 min-h-screen">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Content Area -->
        <div class="flex flex-col flex-1 overflow-x-hidden overflow-y-auto">

            <!-- Overlay mobile -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 lg:hidden">
            </div>

            <!-- Header -->
            @include('components.header')

            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-6">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')
</body>

</html>