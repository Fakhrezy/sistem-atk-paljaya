@extends('layouts.admin')

@section('title', 'Monitoring Pengambilan')

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
                            <h2 class="text-2xl font-semibold text-gray-800">Monitoring Pengambilan</h2>

                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="p-4 mb-6 rounded-lg bg-gray-50">
                    <form method="GET" action="{{ route('admin.monitoring-barang.index') }}"
                        class="flex flex-wrap items-end gap-4">
                        <div class="flex-1 min-w-64">
                            <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Cari nama barang atau pengambil...">
                        </div>

                        <div class="min-w-48">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" name="status"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Status</option>
                                <option value="diajukan" {{ request('status')=='diajukan' ? 'selected' : '' }}>Diajukan
                                </option>
                                <option value="diterima" {{ request('status')=='diterima' ? 'selected' : '' }}>Diterima
                                </option>
                            </select>
                        </div>

                        <div class="min-w-48">
                            <label for="bidang" class="block text-sm font-medium text-gray-700">Bidang</label>
                            <select id="bidang" name="bidang"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Bidang</option>
                                <option value="umum" {{ request('bidang')=='umum' ? 'selected' : '' }}>Umum</option>
                                <option value="perencanaan" {{ request('bidang')=='perencanaan' ? 'selected' : '' }}>
                                    Perencanaan</option>
                                <option value="keuangan" {{ request('bidang')=='keuangan' ? 'selected' : '' }}>Keuangan
                                </option>
                                <option value="operasional" {{ request('bidang')=='operasional' ? 'selected' : '' }}>
                                    Operasional</option>
                                <option value="lainnya" {{ request('bidang')=='lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                        </div>

                        <div class="min-w-48">
                            <label for="jenis_barang" class="block text-sm font-medium text-gray-700">Jenis
                                Barang</label>
                            <select id="jenis_barang" name="jenis_barang"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Jenis</option>
                                <option value="atk" {{ request('jenis_barang')=='atk' ? 'selected' : '' }}>ATK</option>
                                <option value="cetak" {{ request('jenis_barang')=='cetak' ? 'selected' : '' }}>Cetakan
                                </option>
                                <option value="tinta" {{ request('jenis_barang')=='tinta' ? 'selected' : '' }}>Tinta
                                </option>
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 text-gray-700 bg-white border border-gray-300 rounded-md h-9 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                            @if(request('search') || request('bidang') || request('jenis_barang') || request('status'))
                            <a href="{{ route('admin.monitoring-barang.index') }}"
                                class="inline-flex items-center px-4 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md h-9 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                    <!-- Mobile view (hidden on larger screens) -->
                    <div class="block md:hidden">
                        <div class="p-4 space-y-4">
                            @forelse ($monitoringBarang as $index => $item)
                            <div class="p-4 border rounded-lg bg-gray-50">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-sm font-medium text-gray-900" title="{{ $item->nama_barang }}">{{
                                        Str::limit($item->nama_barang, 30) }}</h3>
                                    @if($item->jenis_barang)
                                    @switch($item->jenis_barang)
                                    @case('atk')
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded">
                                        ATK
                                    </span>
                                    @break
                                    @case('cetak')
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">
                                        Cetakan
                                    </span>
                                    @break
                                    @case('tinta')
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-purple-800 bg-purple-100 rounded">
                                        Tinta
                                    </span>
                                    @break
                                    @endswitch
                                    @endif
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-3 text-xs text-gray-600">
                                    <div><span class="font-medium">Pengambil:</span> {{ $item->nama_pengambil }}</div>
                                    <div><span class="font-medium">Bidang:</span> {{ ucfirst($item->bidang) }}</div>
                                    <div><span class="font-medium">Tanggal:</span> {{
                                        \Carbon\Carbon::parse($item->tanggal_ambil)->format('d/m/Y') }}</div>
                                    <div><span class="font-medium">Kredit:</span> <span
                                            class="font-medium text-red-600">{{ number_format($item->kredit, 0, ',',
                                            '.') }}</span></div>
                                </div>
                                @if($item->keterangan)
                                <div class="mb-3 text-xs text-gray-600">
                                    <div class="flex items-start space-x-2">
                                        <i class="mt-1 text-blue-500 fas fa-sticky-note"></i>
                                        <div>
                                            <span class="font-medium">Keterangan:</span>
                                            <p class="mt-1 text-gray-700">{{ $item->keterangan }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    @if($item->status == 'diajukan')
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">
                                        Diajukan
                                    </span>
                                    <div class="flex gap-2">
                                        <button onclick="updateStatus({{ $item->id }}, 'diterima')"
                                            class="px-3 py-1 text-xs text-white transition duration-150 bg-green-600 rounded hover:bg-green-700">
                                            <i class="mr-1 fas fa-check"></i>Terima
                                        </button>
                                        <button onclick="editMonitoring({{ $item->id }})"
                                            class="px-3 py-1 text-xs text-white transition duration-150 bg-blue-600 rounded hover:bg-blue-700">
                                            <i class="mr-1 fas fa-edit"></i>Edit
                                        </button>
                                        <button onclick="deleteMonitoring({{ $item->id }})"
                                            class="px-3 py-1 text-xs text-white transition duration-150 bg-gray-500 rounded hover:bg-gray-600">
                                            <i class="mr-1 fas fa-trash"></i>Hapus
                                        </button>
                                    </div>
                                    @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">
                                        Diterima
                                    </span>
                                    <div class="flex gap-2">
                                        <button onclick="updateStatus({{ $item->id }}, 'diajukan')"
                                            class="px-3 py-1 text-xs text-white transition duration-150 bg-yellow-600 rounded hover:bg-yellow-700">
                                            <i class="mr-1 fas fa-undo"></i>Batalkan
                                        </button>
                                        <button disabled
                                            class="px-3 py-1 text-xs text-gray-400 transition duration-150 bg-gray-300 rounded cursor-not-allowed">
                                            <i class="mr-1 fas fa-edit"></i>Edit
                                        </button>
                                        <button onclick="deleteMonitoring({{ $item->id }})"
                                            class="px-3 py-1 text-xs text-white transition duration-150 bg-red-600 rounded hover:bg-red-700">
                                            <i class="mr-1 fas fa-trash"></i>Hapus
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="py-8 text-center text-gray-500">
                                <i class="mb-2 text-3xl text-gray-400 fas fa-clipboard-list"></i>
                                <p class="text-base font-medium">Belum ada data monitoring barang</p>
                                <p class="text-sm">Data akan muncul setelah ada pengambilan barang yang diajukan</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Desktop view (hidden on mobile) -->
                    <div class="hidden md:block">
                        <table class="w-full table-fixed">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="w-12 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                        No</th>
                                    <th
                                        class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border w-28">
                                        Tanggal</th>
                                    <th
                                        class="w-48 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                        Nama Barang</th>
                                    <th
                                        class="w-20 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                        Jenis</th>
                                    <th
                                        class="w-32 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                        Pengambil</th>
                                    <th
                                        class="w-32 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                        Bidang</th>
                                    <th
                                        class="w-16 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                        Saldo</th>
                                    <th
                                        class="w-16 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                        Kredit</th>
                                    <th
                                        class="w-16 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                        Saldo Akhir</th>
                                    <th
                                        class="w-20 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                        Status</th>
                                    <th
                                        class="w-32 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                        Keterangan</th>
                                    <th
                                        class="w-24 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($monitoringBarang as $index => $item)
                                <tr class="transition-colors duration-200 hover:bg-gray-50">
                                    <td class="px-3 py-3 text-sm text-gray-900 border">
                                        {{ $monitoringBarang->firstItem() + $index }}
                                    </td>
                                    <td class="px-3 py-3 text-sm text-gray-900 border">
                                        {{ \Carbon\Carbon::parse($item->tanggal_ambil)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-3 py-3 text-sm font-medium text-gray-900 break-words border">
                                        {{ $item->nama_barang }}
                                    </td>
                                    <td class="px-3 py-3 text-sm text-gray-900 border">
                                        @if($item->jenis_barang)
                                        @switch($item->jenis_barang)
                                        @case('atk')
                                        ATK
                                        @break
                                        @case('cetak')
                                        Cetakan
                                        @break
                                        @case('tinta')
                                        Tinta
                                        @break
                                        @default
                                        {{ ucfirst($item->jenis_barang) }}
                                        @endswitch
                                        @else
                                        Tidak diketahui
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-sm text-gray-900 break-words border">
                                        {{ $item->nama_pengambil }}
                                    </td>
                                    <td class="px-3 py-3 text-sm text-gray-900 border">
                                        {{ ucfirst($item->bidang) }}
                                    </td>
                                    <td class="px-3 py-3 text-sm text-right text-gray-900 border">
                                        {{ number_format($item->saldo, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-3 text-sm font-medium text-right text-red-600 border">
                                        {{ number_format($item->kredit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-3 text-sm font-medium text-right text-gray-900 border">
                                        {{ number_format($item->saldo_akhir, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-3 text-sm border">
                                        @if($item->status == 'diajukan')
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">
                                            Diajukan
                                        </span>
                                        @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">
                                            Diterima
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-sm text-gray-900 border">
                                        @if($item->keterangan)
                                        <div class="flex items-start space-x-2">
                                            <i class="mt-1 text-xs text-blue-500 fas fa-sticky-note"></i>
                                            <span class="text-sm" title="{{ $item->keterangan }}">
                                                {{ Str::limit($item->keterangan, 50) }}
                                            </span>
                                        </div>
                                        @else
                                        <span class="text-xs italic text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-sm border">
                                        <div class="flex gap-1">
                                            @if($item->status == 'diajukan')
                                            <button onclick="updateStatus({{ $item->id }}, 'diterima')"
                                                class="px-2 py-1 text-xs text-white transition duration-150 bg-green-600 rounded hover:bg-green-700"
                                                title="Terima">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @else
                                            <button onclick="updateStatus({{ $item->id }}, 'diajukan')"
                                                class="px-2 py-1 text-xs text-white transition duration-150 bg-yellow-600 rounded hover:bg-yellow-700"
                                                title="Batalkan">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            @endif
                                            @if($item->status == 'diajukan')
                                            <button onclick="editMonitoring({{ $item->id }})"
                                                class="px-2 py-1 text-xs text-white transition duration-150 bg-blue-600 rounded hover:bg-blue-700"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @else
                                            <button disabled
                                                class="px-2 py-1 text-xs text-gray-400 transition duration-150 bg-gray-300 rounded cursor-not-allowed"
                                                title="Tidak dapat mengedit data yang sudah diterima">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif
                                            <button onclick="deleteMonitoring({{ $item->id }})"
                                                class="px-2 py-1 text-xs text-white transition duration-150 bg-gray-500 rounded hover:bg-gray-600"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="px-3 py-8 text-center text-gray-500 border">
                                        <div class="flex flex-col items-center">
                                            <i class="mb-2 text-3xl text-gray-400 fas fa-clipboard-list"></i>
                                            <p class="text-base font-medium">Belum ada data monitoring barang</p>
                                            <p class="text-sm">Data akan muncul setelah ada pengambilan barang yang
                                                diajukan</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($monitoringBarang->hasPages())
                <div class="mt-6">
                    {{ $monitoringBarang->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Update status function
function updateStatus(id, status) {
    const actionText = status === 'diterima' ? 'menerima' : 'membatalkan penerimaan';
    const confirmTitle = status === 'diterima' ? 'Terima Pengambilan?' : 'Batalkan Penerimaan?';
    const confirmText = `Apakah Anda yakin ingin ${actionText} pengambilan barang ini?`;
    const confirmButtonText = status === 'diterima' ? '<i class="mr-2 fas fa-check"></i>Terima!' : '<i class="mr-2 fas fa-check"></i>Ya, Batalkan!';
    const confirmButtonColor = status === 'diterima' ? '#16a34a' : '#f59e0b';

    Swal.fire({
        title: confirmTitle,
        text: confirmText,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: '#6b7280',
        confirmButtonText: confirmButtonText,
        cancelButtonText: '<i class="mr-2 fas fa-times"></i>Tidak',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memperbarui status pengambilan',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send request
            fetch(`{{ url('/admin/monitoring-barang') }}/${id}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message || 'Status berhasil diperbarui',
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
                        text: data.message || 'Tidak dapat memperbarui status',
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
                    text: 'Terjadi kesalahan saat memperbarui status',
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
                });
            });
        }
    });
}

// Delete monitoring function
function deleteMonitoring(id) {
    Swal.fire({
        title: 'Hapus Data Monitoring?',
        text: 'Yakin ingin menghapus data monitoring ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="mr-2 fas fa-trash"></i>Hapus!',
        cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                text: 'Sedang menghapus data monitoring',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send delete request
            fetch(`{{ url('/admin/monitoring-barang') }}/${id}`, {
                method: 'DELETE',
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
                        text: data.message || 'Data monitoring berhasil dihapus',
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
                        text: data.message || 'Tidak dapat menghapus data monitoring',
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
                    text: 'Terjadi kesalahan saat menghapus data',
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
                });
            });
        }
    });
}

// Edit monitoring function
function editMonitoring(id) {
    // Fetch current data
    fetch(`{{ url('/admin/monitoring-barang') }}/${id}/edit`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const monitoring = data.data;

            Swal.fire({
                title: 'Edit Data Pengambilan',
                html: `
                    <div class="text-left">
                        <div class="p-3 mb-4 rounded-md bg-gray-50">
                            <h4 class="flex items-center mb-3 font-medium text-gray-800">
                                <i class="mr-2 text-blue-500 fas fa-info-circle"></i>
                                Informasi Data
                            </h4>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p class="flex items-center">
                                    <i class="w-4 mr-2 text-gray-500 fas fa-box"></i>
                                    <strong>Nama Barang:</strong> <span class="ml-2">${monitoring.nama_barang}</span>
                                </p>
                                <p class="flex items-center">
                                    <i class="w-4 mr-2 text-gray-500 fas fa-tags"></i>
                                    <strong>Jenis Barang:</strong> <span class="ml-2">${monitoring.jenis_barang.toUpperCase()}</span>
                                </p>
                                <p class="flex items-center">
                                    <i class="w-4 mr-2 text-gray-500 fas fa-user"></i>
                                    <strong>Nama Pengambil:</strong> <span class="ml-2">${monitoring.nama_pengambil}</span>
                                </p>
                                <p class="flex items-center">
                                    <i class="w-4 mr-2 text-gray-500 fas fa-building"></i>
                                    <strong>Bidang:</strong> <span class="ml-2">${monitoring.bidang}</span>
                                </p>
                                <p class="flex items-center">
                                    <i class="w-4 mr-2 text-gray-500 fas fa-calendar-alt"></i>
                                    <strong>Tanggal Ambil:</strong> <span class="ml-2">${new Date(monitoring.tanggal_ambil).toLocaleDateString('id-ID')}</span>
                                </p>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center block mb-2 text-sm font-medium text-gray-700">
                                <i class="mr-2 text-green-500 fas fa-money-bill-wave"></i>
                                Kredit <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="edit_kredit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="${monitoring.kredit}" min="0" step="1" placeholder="Masukkan jumlah kredit">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="flex items-center block mb-2 text-sm font-medium text-gray-500">
                                    <i class="mr-2 text-gray-400 fas fa-wallet"></i>
                                    Saldo
                                </label>
                                <input type="number" class="w-full px-3 py-2 text-gray-500 bg-gray-100 border border-gray-200 rounded-md" value="${monitoring.saldo}" readonly>
                            </div>
                            <div>
                                <label class="flex items-center block mb-2 text-sm font-medium text-gray-500">
                                    <i class="mr-2 text-gray-400 fas fa-calculator"></i>
                                    Saldo Akhir
                                </label>
                                <input type="number" class="w-full px-3 py-2 text-gray-500 bg-gray-100 border border-gray-200 rounded-md" value="${monitoring.saldo_akhir}" readonly>
                            </div>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="mr-2 fas fa-save"></i>Simpan',
                cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
                width: '500px',
                preConfirm: () => {
                    const kredit = document.getElementById('edit_kredit').value;

                    if (!kredit || kredit < 0) {
                        Swal.showValidationMessage('Kredit harus diisi dan tidak boleh negatif!');
                        return false;
                    }

                    return {
                        kredit: parseFloat(kredit)
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menyimpan...',
                        text: 'Sedang menyimpan perubahan kredit',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Send update request
                    fetch(`{{ url('/admin/monitoring-barang') }}/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message || 'Kredit berhasil diperbarui',
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
                                text: data.message || 'Tidak dapat memperbarui kredit',
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
                            text: 'Terjadi kesalahan saat memperbarui kredit',
                            icon: 'error',
                            confirmButtonColor: '#dc2626',
                            confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
                        });
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: data.message || 'Tidak dapat mengambil data monitoring',
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
            text: 'Terjadi kesalahan saat mengambil data',
            icon: 'error',
            confirmButtonColor: '#dc2626',
            confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
        });
    });
}
</script>
@endsection