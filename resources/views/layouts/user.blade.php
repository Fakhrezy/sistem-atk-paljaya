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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full overflow-hidden font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <div class="flex h-screen">
            <!-- Sidebar -->
            <div class="flex-shrink-0 w-64 overflow-y-auto bg-blue-800 shadow-lg">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="flex items-center justify-center h-16 px-4 bg-blue-900">
                        <img src="{{ asset('images/paljaya-logo.png') }}" alt="Logo" class="w-auto h-7">
                    </div>

                    <!-- Navigation -->
                    <nav class="flex flex-col justify-between flex-1 px-3 py-4 bg-blue-800">
                        <div class="space-y-2">
                            <!-- Dashboard Link -->
                            <a href="{{ route('user.dashboard') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-blue-700 {{ request()->routeIs('user.dashboard') ? 'bg-blue-700' : '' }}">
                                <svg class="w-5 h-5 mr-3 text-blue-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                                <span class="truncate">Dashboard</span>
                            </a>

                            <!-- Pengambilan Barang Link -->
                            <a href="{{ route('user.pengambilan.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-blue-700 {{ request()->routeIs('user.pengambilan*') ? 'bg-blue-700' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Pengambilan Barang
                            </a>

                            <!-- Usulan Pengadaan Link -->
                            <a href="{{ route('user.usulan.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-blue-700 {{ request()->routeIs('user.usulan*') ? 'bg-blue-700' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Usulan Pengadaan
                            </a>


                        </div>

                        <!-- User Info & Logout -->
                        <div class="pt-4 mt-4 border-t border-blue-700">
                            <div class="flex items-center px-4 py-2 text-sm text-blue-300">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div class="flex flex-col">
                                    <span class="font-medium text-white">{{ Auth::user()->name }}</span>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-blue-700">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col flex-1 overflow-hidden">
                <!-- Header -->
                <header class="shadow-sm" style="background-color: #0074BC;">
                    <div class="flex items-center justify-between px-6 py-4">
                        <h1 class="text-2xl font-semibold text-white">
                            SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
                        </h1>
                        <div class="flex items-center space-x-4">
                            {{-- <span class="text-sm text-gray-500">
                                @php
                                $hari = [
                                'Sunday' => 'Minggu',
                                'Monday' => 'Senin',
                                'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu'
                                ];
                                $now = now()->setTimezone('Asia/Jakarta');
                                $namaHari = $hari[$now->format('l')];
                                @endphp
                                {{ $namaHari }}, {{ $now->format('d F Y') }}
                            </span> --}}
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-6 overflow-y-auto bg-gray-100">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>

</html>
