@extends('layouts.admin')

@section('title', 'Detail Monitoring Barang')

@section('header')
SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-full">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Detail Monitoring Barang</h2>
                            <p class="mt-1 text-sm text-gray-600">Laporan gabungan monitoring pengambilan dan pengadaan
                                barang</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Sync Button -->
                            <button onclick="syncData()"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition duration-150 bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="mr-2 fas fa-sync-alt"></i>
                                Sinkronisasi Data
                            </button>
                            <!-- Export Button -->
                            <a href="{{ route('admin.detail-monitoring-barang.export', request()->query()) }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition duration-150 bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="mr-2 fas fa-download"></i>
                                Ekspor Data
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="p-4 mb-6 rounded-lg bg-gray-50">
                    <form method="GET" action="{{ route('admin.detail-monitoring-barang.index') }}"
                        class="flex flex-wrap items-end gap-4">

                        <div class="flex-1 min-w-48">
                            <label for="id_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <select id="id_barang" name="id_barang"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Barang</option>
                                @foreach($barangList as $barang)
                                <option value="{{ $barang->id_barang }}" {{ $filters['id_barang']==$barang->id_barang ?
                                    'selected' : '' }}>
                                    {{ $barang->nama_barang }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="min-w-40">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $filters['start_date'] }}"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div class="min-w-40">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $filters['end_date'] }}"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div class="min-w-48">
                            <label for="bidang" class="block text-sm font-medium text-gray-700">Bidang</label>
                            <select id="bidang" name="bidang"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Bidang</option>
                                @foreach($bidangList as $bidang)
                                <option value="{{ $bidang }}" {{ $filters['bidang']==$bidang ? 'selected' : '' }}>
                                    {{ ucfirst($bidang) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="min-w-40">
                            <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
                            <select id="jenis" name="jenis"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Transaksi</option>
                                <option value="debit" {{ $filters['jenis']=='debit' ? 'selected' : '' }}>Debit
                                    (Pengadaan)</option>
                                <option value="kredit" {{ $filters['jenis']=='kredit' ? 'selected' : '' }}>Kredit
                                    (Pengambilan)</option>
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 text-white bg-blue-600 border border-transparent rounded-md h-9 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="mr-2 fas fa-search"></i>Filter
                            </button>
                            @if(array_filter($filters))
                            <a href="{{ route('admin.detail-monitoring-barang.index') }}"
                                class="inline-flex items-center px-4 text-gray-700 bg-white border border-gray-300 rounded-md h-9 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                </script>
                @endif

                @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: '{{ session('error') }}',
                            confirmButtonColor: '#d33'
                        });
                    });
                </script>
                @endif

                <!-- Data Table -->
                <div class="bg-white rounded-lg shadow">
                    <!-- Mobile view -->
                    <div class="hidden lg:hidden">
                        <div class="p-4 space-y-4">
                            @forelse ($detailMonitoring as $index => $item)
                            <div class="p-4 border rounded-lg bg-gray-50">
                                <!-- Header dengan No dan Tanggal -->
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-white bg-blue-600 rounded-full">
                                            {{ $detailMonitoring->firstItem() + $index }}
                                        </span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{
                                                $item->tanggal->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-600" title="{{ $item->nama_barang }}">
                                                <i class="mr-1 text-gray-500 fas fa-box"></i>
                                                {{ Str::limit($item->nama_barang, 25) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Uraian Section -->
                                <div class="mb-4">
                                    <h4 class="mb-2 text-xs font-semibold text-gray-700 uppercase">Uraian</h4>
                                    <div class="space-y-2 text-xs">
                                        @if($item->keterangan)
                                        <div class="flex items-start space-x-2">
                                            <i class="mt-0.5 text-blue-500 fas fa-sticky-note"></i>
                                            <div>
                                                <span class="font-medium text-gray-600">Keterangan:</span>
                                                <p class="text-gray-700">{{ $item->keterangan }}</p>
                                            </div>
                                        </div>
                                        @endif
                                        @if($item->bidang)
                                        <div class="flex items-center space-x-2">
                                            <i class="text-blue-500 fas fa-building"></i>
                                            <span class="font-medium text-gray-600">Bidang:</span>
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded">
                                                {{ ucfirst($item->bidang) }}
                                            </span>
                                        </div>
                                        @endif
                                        @if($item->pengambil)
                                        <div class="flex items-center space-x-2">
                                            <i class="text-blue-500 fas fa-user"></i>
                                            <span class="font-medium text-gray-600">Pengambil:</span>
                                            <span class="text-gray-700">{{ $item->pengambil }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Persediaan Section -->
                                <div>
                                    <h4 class="mb-2 text-xs font-semibold text-gray-700 uppercase">Persediaan</h4>
                                    <div class="grid grid-cols-3 gap-3 text-center">
                                        <div class="p-2 border border-green-200 rounded bg-green-50">
                                            <div class="text-xs font-medium text-green-700">Debit</div>
                                            <div class="text-sm font-bold text-green-600">
                                                {{ $item->debit ? '+' . number_format($item->debit, 0, ',', '.') : '0'
                                                }}
                                            </div>
                                        </div>
                                        <div class="p-2 border border-red-200 rounded bg-red-50">
                                            <div class="text-xs font-medium text-red-700">Kredit</div>
                                            <div class="text-sm font-bold text-red-600">
                                                {{ $item->kredit ? '-' . number_format($item->kredit, 0, ',', '.') : '0'
                                                }}
                                            </div>
                                        </div>
                                        <div class="p-2 border border-blue-200 rounded bg-blue-50">
                                            <div class="text-xs font-medium text-blue-700">Saldo</div>
                                            <div class="text-sm font-bold text-blue-600">
                                                {{ number_format($item->saldo, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="py-8 text-center text-gray-500">
                                <i class="mb-2 text-3xl text-gray-400 fas fa-chart-line"></i>
                                <p class="text-base font-medium">Belum ada data monitoring</p>
                                <p class="text-sm">Klik "Sinkronisasi Data" untuk memuat data dari monitoring barang dan
                                    pengadaan</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Desktop view -->
                    <div class="block">
                        <table class="w-full border-collapse table-auto">
                            <thead>
                                <!-- Header Utama -->
                                <tr class="bg-gray-100">
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border"
                                        rowspan="2">
                                        No
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border"
                                        rowspan="2">
                                        Tanggal
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border"
                                        rowspan="2">
                                        Nama Barang
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border"
                                        colspan="3">
                                        Uraian
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border"
                                        colspan="3">
                                        Persediaan
                                    </th>
                                </tr>
                                <!-- Sub Header -->
                                <tr class="bg-gray-100">
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Keterangan</th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Bidang
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Pengambil
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Debit
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Kredit
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Saldo
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($detailMonitoring as $index => $item)
                                <tr class="transition-colors duration-200 hover:bg-gray-50">
                                    <!-- No -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $detailMonitoring->firstItem() + $index }}
                                    </td>
                                    <!-- Tanggal -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $item->tanggal->format('d/m/Y') }}
                                    </td>
                                    <!-- Nama Barang -->
                                    <td class="px-3 py-3 text-sm text-gray-900 border">
                                        {{ $item->barang->nama_barang ?? $item->nama_barang ?? '-' }}
                                    </td>
                                    <!-- Uraian: Keterangan -->
                                    <td class="px-3 py-3 text-sm text-gray-900 border">
                                        @if($item->keterangan)
                                        <div class="flex items-start space-x-2">
                                            <i class="mt-1 text-xs text-blue-500 fas fa-sticky-note"></i>
                                            <span title="{{ $item->keterangan }}">
                                                {{ Str::limit($item->keterangan, 30) }}
                                            </span>
                                        </div>
                                        @else
                                        <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <!-- Uraian: Bidang -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $item->bidang ? ucfirst($item->bidang) : '-' }}
                                    </td>
                                    <!-- Uraian: Pengambil -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $item->pengambil ? Str::limit($item->pengambil, 15) : '-' }}
                                    </td>
                                    <!-- Persediaan: Debit -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $item->debit ? number_format($item->debit, 0, ',', '.') : '0' }}
                                    </td>
                                    <!-- Persediaan: Kredit -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $item->kredit ? number_format($item->kredit, 0, ',', '.') : '0' }}
                                    </td>
                                    <!-- Persediaan: Saldo -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ number_format($item->saldo, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-3 py-8 text-center text-gray-500 border">
                                        <div class="flex flex-col items-center">
                                            <i class="mb-2 text-3xl text-gray-400 fas fa-chart-line"></i>
                                            <p class="text-base font-medium">Belum ada data monitoring</p>
                                            <p class="text-sm">Klik "Sinkronisasi Data" untuk memuat data dari
                                                monitoring barang dan pengadaan</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($detailMonitoring->hasPages())
                <div class="mt-6">
                    {{ $detailMonitoring->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Sync data function
function syncData() {
    Swal.fire({
        title: 'Sinkronisasi Data?',
        text: 'Proses ini akan menyinkronkan data dari monitoring barang dan pengadaan ke detail monitoring.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="mr-2 fas fa-sync-alt"></i>Ya, Sinkronisasi!',
        cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Melakukan Sinkronisasi...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send sync request
            fetch('{{ route('admin.detail-monitoring-barang.sync') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat melakukan sinkronisasi',
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
                });
            });
        }
    });
}
</script>
@endsection
