@extends('layouts.admin')

@section('title', 'Detail Monitoring')

@section('header')
    SISTEM INFORMASI MONITORING BARANG ATK CETAKAN & TINTA
@endsection

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6 overflow-hidden bg-white shadow-lg sm:rounded-lg">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $monitoring->id_monitoring }}</h3>
                        <p class="text-sm text-blue-100">Detail Transaksi Monitoring</p>
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('admin.monitoring.edit', $monitoring->id_monitoring) }}"
                           class="px-4 py-2 font-medium text-white transition-colors duration-200 bg-yellow-500 rounded-lg hover:bg-yellow-600">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.monitoring') }}"
                           class="px-4 py-2 font-medium text-white transition-colors duration-200 bg-gray-500 rounded-lg hover:bg-gray-600">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
            <!-- Informasi Monitoring -->
            <div class="overflow-hidden bg-white shadow-lg sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h4 class="flex items-center text-lg font-semibold text-gray-800">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Informasi Transaksi
                    </h4>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">ID Monitoring</span>
                        <span class="font-semibold text-gray-900">{{ $monitoring->id_monitoring }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Tanggal & Waktu</span>
                        <span class="font-semibold text-gray-900">{{ $monitoring->tanggal->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex items-start justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Bidang</span>
                        <span class="font-semibold text-right text-gray-900">{{ $monitoring->bidang }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Pengambil</span>
                        <span class="font-semibold text-gray-900">{{ $monitoring->pengambil }}</span>
                    </div>
                    @if($monitoring->keterangan)
                    <div class="py-2">
                        <span class="block mb-2 text-sm font-medium text-gray-600">Keterangan</span>
                        <div class="p-3 rounded-lg bg-gray-50">
                            <p class="text-sm text-gray-700">{{ $monitoring->keterangan }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Barang -->
            <div class="overflow-hidden bg-white shadow-lg sm:rounded-lg">
                <div class="px-6 py-4 border-b border-blue-200 bg-blue-50">
                    <h4 class="flex items-center text-lg font-semibold text-gray-800">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        Informasi Barang
                    </h4>
                </div>
                <div class="p-6 space-y-4">
                    @if($monitoring->barang)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">ID Barang</span>
                        <span class="font-semibold text-gray-900">{{ $monitoring->barang->id_barang }}</span>
                    </div>
                    <div class="flex items-start justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Nama Barang</span>
                        <span class="font-semibold text-right text-gray-900">{{ $monitoring->barang->nama_barang }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Jenis</span>
                        <span class="px-2 py-1 text-xs font-medium text-blue-800 uppercase bg-blue-100 rounded-full">
                            {{ $monitoring->barang->jenis }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm font-medium text-gray-600">Stok Saat Ini</span>
                        <span class="text-lg font-bold text-blue-600">{{ number_format($monitoring->barang->stok) }}</span>
                    </div>
                    @else
                    <div class="py-8 text-center">
                        <svg class="w-12 h-12 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="font-medium text-red-600">Barang tidak ditemukan</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Summary Card -->
            <div class="overflow-hidden bg-white shadow-lg sm:rounded-lg">
                <div class="px-6 py-4 border-b border-green-200 bg-green-50">
                    <h4 class="flex items-center text-lg font-semibold text-gray-800">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Ringkasan Transaksi
                    </h4>
                </div>
                <div class="p-6 space-y-4">
                    <div class="p-4 text-center border border-green-200 rounded-lg bg-green-50">
                        <div class="text-2xl font-bold text-green-600">
                            +{{ number_format($monitoring->debit) }}
                        </div>
                        <div class="text-sm font-medium text-green-700">Debit (Masuk)</div>
                        @if($monitoring->debit == 0)
                            <div class="mt-1 text-xs text-gray-500">Tidak ada penambahan</div>
                        @endif
                    </div>

                    <div class="p-4 text-center border border-red-200 rounded-lg bg-red-50">
                        <div class="text-2xl font-bold text-red-600">
                            -{{ number_format($monitoring->kredit) }}
                        </div>
                        <div class="text-sm font-medium text-red-700">Kredit (Keluar)</div>
                        @if($monitoring->kredit == 0)
                            <div class="mt-1 text-xs text-gray-500">Tidak ada pengurangan</div>
                        @endif
                    </div>

                    <div class="p-4 text-center border border-blue-200 rounded-lg bg-blue-50">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ number_format($monitoring->saldo) }}
                        </div>
                        <div class="text-sm font-medium text-blue-700">Saldo Akhir</div>
                        <div class="mt-1 text-xs text-gray-500">Setelah transaksi ini</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="overflow-hidden bg-white shadow-lg sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-center space-x-4">
                    <form action="{{ route('admin.monitoring.destroy', $monitoring->id_monitoring) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data monitoring ini? Stok barang akan dikembalikan.')"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center px-6 py-2 font-medium text-white transition-colors duration-200 bg-red-500 rounded-lg hover:bg-red-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
