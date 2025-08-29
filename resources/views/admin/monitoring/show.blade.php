@extends('layouts.admin')

@section('title', 'Detail Monitoring')

@section('header')
    Detail Monitoring Barang
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $monitoring->id_monitoring }}</h3>
                        <p class="text-blue-100 text-sm">Detail Transaksi Monitoring</p>
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('admin.monitoring.edit', $monitoring->id_monitoring) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.monitoring') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Informasi Monitoring -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Informasi Transaksi
                    </h4>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">ID Monitoring</span>
                        <span class="font-semibold text-gray-900">{{ $monitoring->id_monitoring }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Tanggal & Waktu</span>
                        <span class="font-semibold text-gray-900">{{ $monitoring->tanggal->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-start py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Keperluan</span>
                        <span class="font-semibold text-gray-900 text-right">{{ $monitoring->keperluan }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Pengambil</span>
                        <span class="font-semibold text-gray-900">{{ $monitoring->pengambil }}</span>
                    </div>
                    @if($monitoring->keterangan)
                    <div class="py-2">
                        <span class="text-sm font-medium text-gray-600 block mb-2">Keterangan</span>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-gray-700 text-sm">{{ $monitoring->keterangan }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Barang -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        Informasi Barang
                    </h4>
                </div>
                <div class="p-6 space-y-4">
                    @if($monitoring->barang)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">ID Barang</span>
                        <span class="font-semibold text-gray-900">{{ $monitoring->barang->id_barang }}</span>
                    </div>
                    <div class="flex justify-between items-start py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Nama Barang</span>
                        <span class="font-semibold text-gray-900 text-right">{{ $monitoring->barang->nama_barang }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Jenis</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full uppercase">
                            {{ $monitoring->barang->jenis }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-600">Stok Saat Ini</span>
                        <span class="font-bold text-blue-600 text-lg">{{ number_format($monitoring->barang->stok) }}</span>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-red-600 font-medium">Barang tidak ditemukan</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Summary Card -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="bg-green-50 px-6 py-4 border-b border-green-200">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Ringkasan Transaksi
                    </h4>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="text-2xl font-bold text-green-600">
                            +{{ number_format($monitoring->debit) }}
                        </div>
                        <div class="text-sm text-green-700 font-medium">Debit (Masuk)</div>
                        @if($monitoring->debit == 0)
                            <div class="text-xs text-gray-500 mt-1">Tidak ada penambahan</div>
                        @endif
                    </div>

                    <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                        <div class="text-2xl font-bold text-red-600">
                            -{{ number_format($monitoring->kredit) }}
                        </div>
                        <div class="text-sm text-red-700 font-medium">Kredit (Keluar)</div>
                        @if($monitoring->kredit == 0)
                            <div class="text-xs text-gray-500 mt-1">Tidak ada pengurangan</div>
                        @endif
                    </div>

                    <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ number_format($monitoring->saldo) }}
                        </div>
                        <div class="text-sm text-blue-700 font-medium">Saldo Akhir</div>
                        <div class="text-xs text-gray-500 mt-1">Setelah transaksi ini</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-center space-x-4">
                    <form action="{{ route('admin.monitoring.destroy', $monitoring->id_monitoring) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data monitoring ini? Stok barang akan dikembalikan.')"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200 flex items-center">
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
