@extends('layouts.admin')

@section('title', 'Monitoring Barang')

@section('header')
    SISTEM INFORMASI MONITORING BARANG ATK CETAKAN & TINTA
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Header dan Tombol Tambah -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">Data Monitoring Barang</h3>
                    <a href="{{ route('admin.monitoring.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Tambah Monitoring
                    </a>
                </div>

                <!-- Form Filter -->
                <form method="GET" action="{{ route('admin.monitoring') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Cari ID, bidang, pengambil..."
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="tanggal_dari" class="block text-sm font-medium text-gray-700">Tanggal Dari</label>
                            <input type="date" name="tanggal_dari" id="tanggal_dari" value="{{ request('tanggal_dari') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700">Tanggal Sampai</label>
                            <input type="date" name="tanggal_sampai" id="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Filter
                            </button>
                            <a href="{{ route('admin.monitoring') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Pagination Controls -->
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center">
                        <span class="mr-2">Tampilkan:</span>
                        <form method="GET" action="{{ route('admin.monitoring') }}" class="inline">
                            @foreach(request()->except('per_page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="per_page" onchange="this.form.submit()" class="border border-gray-300 rounded px-2 py-1">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </form>
                        <span class="ml-2">entri</span>
                    </div>
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $monitoring->firstItem() }} sampai {{ $monitoring->lastItem() }} dari {{ $monitoring->total() }} hasil
                    </div>
                </div>

                <!-- Tabel Monitoring -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID Monitoring
                                </th>
                                <th class="px-4 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-4 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Barang
                                </th>
                                <th class="px-4 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bidang
                                </th>
                                <th class="px-4 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pengambil
                                </th>
                                <th class="px-4 py-3 border-b text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Debit
                                </th>
                                <th class="px-4 py-3 border-b text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kredit
                                </th>
                                <th class="px-4 py-3 border-b text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Saldo
                                </th>
                                <th class="px-4 py-3 border-b text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($monitoring as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item->id_monitoring }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->tanggal->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->barang->nama_barang ?? 'Barang tidak ditemukan' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->bidang }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->pengambil }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                    @if($item->debit > 0)
                                        <span class="text-green-600 font-semibold">{{ number_format($item->debit) }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                    @if($item->kredit > 0)
                                        <span class="text-red-600 font-semibold">{{ number_format($item->kredit) }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-center font-semibold">
                                    {{ number_format($item->saldo) }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-2 justify-center">
                                        <a href="{{ route('admin.monitoring.show', $item->id_monitoring) }}"
                                           class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.monitoring.edit', $item->id_monitoring) }}"
                                           class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.monitoring.destroy', $item->id_monitoring) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data monitoring ini? Stok barang akan dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-4 py-4 text-center text-gray-500">
                                    Tidak ada data monitoring
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-6">
                    {{ $monitoring->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
