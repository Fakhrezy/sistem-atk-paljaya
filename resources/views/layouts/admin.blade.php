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

    <!-- Custom Styles for Notification Badge -->
    <style>
        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .notification-badge:hover {
            animation: none;
            transform: scale(1.1);
        }
    </style>
</head>

<body class="h-full overflow-hidden font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <div class="flex h-screen">
            <!-- Sidebar -->
            <div class="flex-shrink-0 w-64 overflow-y-auto bg-gray-800 shadow-lg" style="z-index: 50;">
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
                                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-blue-400' : 'text-white' }}">
                                <svg class="w-5 h-5 mr-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                                <span class="truncate">Dashboard</span>
                            </a>

                            <!-- Manajemen Barang Dropdown -->
                            <div class="relative">
                                <button onclick="toggleDropdown('manajemenBarang')"
                                    class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.barang*', 'admin.pengambilan*', 'admin.usulan*') ? 'bg-gray-700 text-blue-400' : 'text-white' }}">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                        <span class="truncate">Manajemen Barang</span>
                                    </div>
                                    <svg id="manajemenBarang-icon" class="w-4 h-4 transition-transform duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div id="manajemenBarang-menu"
                                    class="mt-2 ml-4 space-y-1 {{ request()->routeIs('admin.barang*', 'admin.pengambilan*', 'admin.usulan*') ? '' : 'hidden' }}">
                                    <!-- Data Barang -->
                                    <a href="{{ route('admin.barang') }}"
                                        class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.barang*') ? 'bg-gray-700 text-blue-400' : 'text-gray-300 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                            </path>
                                        </svg>
                                        Data Barang
                                    </a>

                                    <!-- Pengambilan Barang -->
                                    <a href="{{ route('admin.pengambilan.index') }}"
                                        class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.pengambilan*') ? 'bg-gray-700 text-blue-400' : 'text-gray-300 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pengambilan Barang
                                    </a>

                                    <!-- Pengadaan Barang -->
                                    <a href="{{ route('admin.usulan.index') }}"
                                        class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.usulan*') ? 'bg-gray-700 text-blue-400' : 'text-gray-300 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pengadaan Barang
                                    </a>
                                </div>
                            </div>

                            <!-- Monitoring Barang Dropdown -->
                            <div class="relative">
                                <button onclick="toggleDropdown('monitoringBarang')"
                                    class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.monitoring-barang*', 'admin.monitoring-pengadaan*', 'admin.detail-monitoring-barang*') ? 'bg-gray-700 text-blue-400' : 'text-white' }}">
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            @if((isset($notifications['monitoring_pengambilan']) &&
                                            $notifications['monitoring_pengambilan'] > 0) ||
                                            (isset($notifications['monitoring_pengadaan']) &&
                                            $notifications['monitoring_pengadaan'] > 0))
                                            <span
                                                class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full shadow-lg notification-badge"></span>
                                            @endif
                                        </div>
                                        <span class="truncate">Monitoring Barang</span>
                                    </div>
                                    <svg id="monitoringBarang-icon" class="w-4 h-4 transition-transform duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div id="monitoringBarang-menu"
                                    class="mt-2 ml-4 space-y-1 {{ request()->routeIs('admin.monitoring-barang*', 'admin.monitoring-pengadaan*', 'admin.detail-monitoring-barang*') ? '' : 'hidden' }}">
                                    <!-- Monitoring Pengambilan -->
                                    <a href="{{ route('admin.monitoring-barang.index') }}"
                                        class="flex items-center justify-between px-4 py-2 text-sm rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.monitoring-barang*') ? 'bg-gray-700 text-blue-400' : 'text-gray-300 hover:text-white' }}">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                                </path>
                                            </svg>
                                            Monitoring Pengambilan
                                        </div>
                                        @if(isset($notifications['monitoring_pengambilan']) &&
                                        $notifications['monitoring_pengambilan'] > 0)
                                        <span
                                            class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full shadow-lg notification-badge"
                                            title="{{ $notifications['monitoring_pengambilan'] }} pengambilan menunggu persetujuan">
                                            {{ $notifications['monitoring_pengambilan'] }}
                                        </span>
                                        @endif
                                    </a>

                                    <!-- Monitoring Pengadaan -->
                                    <a href="{{ route('admin.monitoring-pengadaan.index') }}"
                                        class="flex items-center justify-between px-4 py-2 text-sm rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.monitoring-pengadaan*') ? 'bg-gray-700 text-blue-400' : 'text-gray-300 hover:text-white' }}">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                                </path>
                                            </svg>
                                            Monitoring Pengadaan
                                        </div>
                                        @if(isset($notifications['monitoring_pengadaan']) &&
                                        $notifications['monitoring_pengadaan'] > 0)
                                        <span
                                            class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full shadow-lg notification-badge"
                                            title="{{ $notifications['monitoring_pengadaan'] }} pengadaan menunggu persetujuan">
                                            {{ $notifications['monitoring_pengadaan'] }}
                                        </span>
                                        @endif
                                    </a>

                                    <!-- Detail Monitoring Barang -->
                                    <a href="{{ route('admin.detail-monitoring-barang.index') }}"
                                        class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.detail-monitoring-barang*') ? 'bg-gray-700 text-blue-400' : 'text-gray-300 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        Detail Monitoring
                                    </a>
                                </div>
                            </div>

                            <!-- Data Triwulan Link -->
                            <a href="{{ route('admin.triwulan.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.triwulan*') ? 'bg-gray-700 text-blue-400' : 'text-white' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Data Triwulan
                            </a>

                            {{--
                            <!-- Laporan Triwulan Link -->
                            <a href="{{ route('admin.laporan-triwulan.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.laporan-triwulan*') ? 'bg-gray-700 text-blue-400' : 'text-white' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Laporan Triwulan
                            </a> --}}

                            <!-- Users Link -->
                            <a href="{{ route('admin.users') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.users*') ? 'bg-gray-700 text-blue-400' : 'text-white' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                Kelola Users
                            </a>
                        </div>

                        <!-- Logout -->
                        <div class="pt-4 mt-auto">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-700">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
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
                <div class="shadow" style="z-index: 45; background-color: #0074BC;">
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

    <script>
        function toggleDropdown(menuId) {
            const menu = document.getElementById(menuId + '-menu');
            const icon = document.getElementById(menuId + '-icon');

            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                menu.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

                // Auto-expand submenu if current route matches
        document.addEventListener('DOMContentLoaded', function() {
            const currentRoute = window.location.pathname;

            // Routes for Manajemen Barang dropdown
            const manajemenBarangRoutes = [
                '/admin/barang',
                '/admin/pengambilan',
                '/admin/usulan'
            ];

            // Routes for Monitoring Barang dropdown
            const monitoringBarangRoutes = [
                '/admin/monitoring-barang',
                '/admin/monitoring-pengadaan',
                '/admin/detail-monitoring-barang'
            ];

            // Check and expand Manajemen Barang dropdown
            const isManajemenBarangActive = manajemenBarangRoutes.some(route =>
                currentRoute.startsWith(route)
            );

            if (isManajemenBarangActive) {
                const menu = document.getElementById('manajemenBarang-menu');
                const icon = document.getElementById('manajemenBarang-icon');
                if (menu && icon) {
                    menu.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                }
            }

            // Check and expand Monitoring Barang dropdown
            const isMonitoringBarangActive = monitoringBarangRoutes.some(route =>
                currentRoute.startsWith(route)
            );

            if (isMonitoringBarangActive) {
                const menu = document.getElementById('monitoringBarang-menu');
                const icon = document.getElementById('monitoringBarang-icon');
                if (menu && icon) {
                    menu.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                }
            }

            // Refresh notification badges every 30 seconds
            setInterval(function() {
                refreshNotificationBadges();
            }, 30000);
        });

        // Function to refresh notification badges via AJAX
        function refreshNotificationBadges() {
            fetch('/admin/notifications/count', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateBadge('monitoring-pengambilan', data.notifications.monitoring_pengambilan);
                    updateBadge('monitoring-pengadaan', data.notifications.monitoring_pengadaan);
                    updateMonitoringHeaderBadge(data.notifications.monitoring_pengambilan, data.notifications.monitoring_pengadaan);
                }
            })
            .catch(error => {
                console.log('Notification update error:', error);
            });
        }

        // Update individual badge
        function updateBadge(type, count) {
            const links = document.querySelectorAll(`a[href*="${type}"]`);
            links.forEach(link => {
                let badge = link.querySelector('.notification-badge');

                if (count > 0) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.className = 'notification-badge inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full shadow-lg';
                        const container = link.querySelector('div:last-child') || link;
                        container.appendChild(badge);
                    }
                    badge.textContent = count;
                    badge.title = `${count} ${type.includes('pengambilan') ? 'pengambilan' : 'pengadaan'} menunggu persetujuan`;
                } else if (badge) {
                    badge.remove();
                }
            });
        }

        // Update monitoring header badge
        function updateMonitoringHeaderBadge(pengambilanCount, pengadaanCount) {
            const monitoringButton = document.querySelector('button[onclick="toggleDropdown(\'monitoringBarang\')"]');
            if (!monitoringButton) return;

            const iconContainer = monitoringButton.querySelector('.relative');
            let headerBadge = iconContainer.querySelector('.notification-badge');

            if ((pengambilanCount > 0) || (pengadaanCount > 0)) {
                if (!headerBadge) {
                    headerBadge = document.createElement('span');
                    headerBadge.className = 'absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full shadow-lg notification-badge';
                    iconContainer.appendChild(headerBadge);
                }
            } else if (headerBadge) {
                headerBadge.remove();
            }
        }
    </script>
</body>

</html>
