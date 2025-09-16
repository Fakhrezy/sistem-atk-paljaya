<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>sismon paljaya</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-pal.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full overflow-hidden font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <div class="flex h-screen">
            <!-- Sidebar -->
            <div class="flex-shrink-0 w-64 overflow-y-auto bg-gray-800 shadow-lg">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="flex items-center justify-center h-16 px-4 bg-gray-900">
                        <img src="{{ asset('images/paljaya-logo.png') }}" alt="Logo" class="w-auto h-7">
                    </div>

                    <!-- Navigation -->
                    <nav class="flex flex-col justify-between flex-1 px-3 py-4 bg-gray-800">
                        <div class="space-y-2">
                            <!-- Dashboard Link -->
                            <a href="{{ route('admin.dashboard') }}"
                               class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                                <svg class="w-5 h-5 mr-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span class="truncate">Dashboard</span>
                            </a>

                            <!-- Barang Link -->
                            <a href="{{ route('admin.barang') }}"
                               class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.barang') ? 'bg-gray-700' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                Data Barang
                            </a>

                            <!-- Users Link -->
                            <a href="{{ route('admin.users') }}"
                               class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.users*') ? 'bg-gray-700' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Kelola Users
                            </a>

                            <!-- Monitoring Pengambilan Link -->
                            <a href="{{ route('admin.monitoring-barang.index') }}"
                               class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.monitoring-barang*') ? 'bg-gray-700' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                Monitoring Pengambilan
                            </a>

                            <!-- Monitoring Pengadaan Link -->
                            <a href="{{ route('admin.monitoring-pengadaan.index') }}"
                               class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.monitoring-pengadaan*') ? 'bg-gray-700' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Monitoring Pengadaan
                            </a>
                        </div>

                        <!-- Logout -->
                        <div class="pt-4 mt-auto">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();"
                                   class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-700">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </a>
                            </form>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col flex-1 overflow-hidden">
                <!-- Top Navigation -->
                <div class="bg-blue-500 shadow">
                    <div class="px-6 py-4">
                        <h2 class="text-2xl font-bold leading-tight text-white">
                            @yield('header', 'Dashboard')
                        </h2>
                    </div>
                </div>

                <!-- Page Content -->
                <main class="flex-1 p-4 overflow-x-hidden overflow-y-auto bg-gray-100">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
