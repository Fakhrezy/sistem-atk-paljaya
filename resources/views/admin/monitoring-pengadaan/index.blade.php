@extends('layouts.admin')

@section('title', 'Monitoring Pengadaan')

@section('header')
    SISTEM MONITORING BARANG HABIS PAKAI
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-full">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Monitoring Pengadaan</h2>

                        </div>
                    </div>
                </div>

            <!-- Filters -->
            <div class="p-4 mb-6 rounded-lg bg-gray-50">
                <form action="{{ route('admin.monitoring-pengadaan.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-64">
                        <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Cari nama barang...">
                    </div>

                    <div class="min-w-48">
                        <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis Barang</label>
                        <select name="jenis" id="jenis" class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Semua Jenis</option>
                            <option value="atk" {{ request('jenis') === 'atk' ? 'selected' : '' }}>ATK</option>
                            <option value="cetak" {{ request('jenis') === 'cetak' ? 'selected' : '' }}>Cetakan</option>
                            <option value="tinta" {{ request('jenis') === 'tinta' ? 'selected' : '' }}>Tinta</option>
                        </select>
                    </div>

                    <div class="min-w-48">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Semua Status</option>
                            <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="terima" {{ request('status') === 'terima' ? 'selected' : '' }}>Terima</option>
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 text-gray-700 bg-white border border-gray-300 rounded-md h-9 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        @if(request('search') || request('jenis') || request('status'))
                            <a href="{{ route('admin.monitoring-pengadaan.index') }}" class="inline-flex items-center px-4 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md h-9 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow">
                <table class="w-full table-fixed">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="w-12 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">No</th>
                            <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border w-28">Tanggal</th>
                            <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border w-36">Barang</th>
                            <th class="w-24 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Jenis</th>
                            <th class="w-16 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Jumlah</th>
                            <th class="w-20 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Satuan</th>
                            <th class="w-24 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Status</th>
                            <th class="w-32 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Keterangan</th>
                            <th class="w-20 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pengadaans as $index => $pengadaan)
                            <tr class="transition-colors duration-200 hover:bg-gray-50">
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    {{ $pengadaan->tanggal->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-3 py-3 text-sm font-medium text-gray-900 truncate border" title="{{ $pengadaan->barang->nama_barang }}">
                                    {{ $pengadaan->barang->nama_barang }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    @switch($pengadaan->barang->jenis)
                                        @case('atk')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded">
                                                ATK
                                            </span>
                                            @break
                                        @case('cetak')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">
                                                Cetakan
                                            </span>
                                            @break
                                        @case('tinta')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-purple-800 bg-purple-100 rounded">
                                                Tinta
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded">
                                                {{ ucfirst($pengadaan->barang->jenis) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-3 py-3 text-sm text-right text-gray-900 border">
                                    {{ $pengadaan->debit }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded">
                                        {{ $pengadaan->barang->satuan }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-sm border">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded
                                        {{ $pengadaan->status === 'proses' ? 'text-yellow-800 bg-yellow-100' : 'text-green-800 bg-green-100' }}">
                                        {{ ucfirst($pengadaan->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-500 border">
                                    {{ $pengadaan->keterangan ?: '-' }}
                                </td>
                                <td class="px-3 py-3 text-sm border">
                                    <div class="flex gap-1">
                                        @if($pengadaan->status === 'proses')
                                            <button onclick="updateStatus({{ $pengadaan->id }}, 'terima')"
                                                    class="px-2 py-1 text-xs text-white transition duration-150 bg-green-600 rounded hover:bg-green-700"
                                                    title="Terima">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @else
                                            <button onclick="updateStatus({{ $pengadaan->id }}, 'proses')"
                                                    class="px-2 py-1 text-xs text-white transition duration-150 bg-yellow-600 rounded hover:bg-yellow-700"
                                                    title="Batalkan">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-8 text-center text-gray-500 border">
                                    <div class="flex flex-col items-center">
                                        <i class="mb-2 text-3xl text-gray-400 fas fa-clipboard-list"></i>
                                        <p class="text-base font-medium">Belum ada data pengadaan</p>
                                        <p class="text-sm">Data akan muncul setelah ada pengajuan pengadaan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    window.updateStatus = function(id, status) {
        const confirmTitle = status === 'terima' ? 'Terima Pengadaan?' : 'Batalkan Status?';
    const confirmText = status === 'terima' ?
        'Apakah Anda yakin ingin menerima pengadaan ini? Stok barang akan bertambah sesuai jumlah pengadaan.' :
        'Apakah Anda yakin ingin membatalkan status pengadaan ini? Stok barang akan dikurangi kembali.';
    const confirmButtonText = status === 'terima' ? '<i class="mr-2 fas fa-check"></i>Terima!' : '<i class="mr-2 fas fa-times"></i>Batalkan!';
    const confirmButtonColor = status === 'terima' ? '#16a34a' : '#f59e0b';

    Swal.fire({
        title: confirmTitle,
        text: confirmText,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: '#dc2626',
        confirmButtonText: confirmButtonText,
        cancelButtonText: '<i class="mr-2 fas fa-ban"></i>Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memperbarui status dan stok barang',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('status', status);

            fetch(`/admin/monitoring-pengadaan/${id}/status`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const actionText = status === 'terima' ? 'diterima' : 'dibatalkan';
                    Swal.fire({
                        title: 'Berhasil!',
                        text: `Pengadaan berhasil ${actionText} dan stok barang telah diperbarui`,
                        icon: 'success',
                        confirmButtonColor: '#16a34a',
                        confirmButtonText: '<i class="mr-2 fas fa-check"></i>OK'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan saat mengubah status dan stok barang.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.message,
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
                });
            });
        }
    });
    };
});
</script>
@endpush
@endsection
