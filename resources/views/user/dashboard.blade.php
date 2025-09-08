@extends('layouts.user')

@section('title', 'Dashboard User')

@section('header')
    Dashboard User
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-full">
        <!-- Welcome Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h2>
                    </div>
                    <div class="text-right">
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
                        <p class="text-sm text-gray-500">{{ $namaHari }}, {{ $now->format('d F Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $now->format('H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Pengambilan Barang Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Pengambilan Barang</h3>
                            <p class="text-sm text-gray-600">Ambil barang yang tersedia</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('user.pengambilan.index') }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Lihat Barang Tersedia
                        </a>
                    </div>
                </div>
            </div>

            <!-- Riwayat Card (Future Feature) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg opacity-50">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Riwayat Pengambilan</h3>
                            <p class="text-sm text-gray-600">Lihat riwayat pengambilan barang</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button disabled
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest cursor-not-allowed">
                            Segera Tersedia
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
