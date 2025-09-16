@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('header')
    SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
@endsection

@section('content')
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Statistik Barang</h2>

                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <!-- Total Barang -->
                        <div class="overflow-hidden rounded-lg shadow bg-gradient-to-r from-blue-500 to-blue-600">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
                                        <dl>
                                            <dt class="text-sm font-medium text-blue-100 truncate">Total Barang</dt>
                                            <dd class="text-lg font-medium text-white">{{ $stats['total'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ATK -->
                        <div class="overflow-hidden rounded-lg shadow bg-gradient-to-r from-green-500 to-green-600">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
                                        <dl>
                                            <dt class="text-sm font-medium text-green-100 truncate">ATK</dt>
                                            <dd class="text-lg font-medium text-white">{{ $stats['atk'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cetak -->
                        <div class="overflow-hidden rounded-lg shadow bg-gradient-to-r from-yellow-500 to-yellow-600">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
                                        <dl>
                                            <dt class="text-sm font-medium text-yellow-100 truncate">Cetakan</dt>
                                            <dd class="text-lg font-medium text-white">{{ $stats['cetak'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tinta -->
                        <div class="overflow-hidden rounded-lg shadow bg-gradient-to-r from-purple-500 to-purple-600">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14.5v3.5c0 1.657-3.134 3-7 3s-7-1.343-7-3v-3.5m14 0c0 1.657-3.134 3-7 3s-7-1.343-7-3m14 0v-2c0-1.657-3.134-3-7-3s-7 1.343-7 3v2m14 0c0 1.657-3.134 3-7 3s-7-1.343-7-3"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 w-0 ml-5">
                                        <dl>
                                            <dt class="text-sm font-medium text-purple-100 truncate">Tinta</dt>
                                            <dd class="text-lg font-medium text-white">{{ $stats['tinta'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
