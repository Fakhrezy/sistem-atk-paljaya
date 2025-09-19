@extends('layouts.admin')

@section('title', 'Detail Pengambilan')

@section('header')
    DETAIL PENGAMBILAN BARANG
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-4xl mx-auto">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-semibold text-gray-800">Detail Pengambilan</h2>
                        <a href="{{ route('admin.pengambilan.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            <i class="mr-2 fas fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="p-4 mb-4 border rounded-lg bg-gray-50">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <h3 class="flex items-center mb-3 font-medium text-gray-900">
                                <i class="mr-2 text-blue-500 fas fa-info-circle"></i>
                                Informasi Pengambilan
                            </h3>
                            <div class="space-y-2">
                                <p class="flex items-center text-sm text-gray-600">
                                    <i class="w-5 mr-2 text-gray-400 fas fa-calendar"></i>
                                    <span class="font-medium">Tanggal:</span>
                                    <span class="ml-2">{{ $pengambilan->created_at->format('d/m/Y H:i') }}</span>
                                </p>
                                <p class="flex items-center text-sm text-gray-600">
                                    <i class="w-5 mr-2 text-gray-400 fas fa-user"></i>
                                    <span class="font-medium">Pengambil:</span>
                                    <span class="ml-2">{{ $pengambilan->user->name }}</span>
                                </p>
                                <p class="flex items-center text-sm text-gray-600">
                                    <i class="w-5 mr-2 text-gray-400 fas fa-building"></i>
                                    <span class="font-medium">Bidang:</span>
                                    <span class="ml-2">{{ ucfirst($pengambilan->bidang) }}</span>
                                </p>
                                <p class="flex items-center text-sm text-gray-600">
                                    <i class="w-5 mr-2 text-gray-400 fas fa-tag"></i>
                                    <span class="font-medium">Status:</span>
                                    <span class="ml-2">
                                        @switch($pengambilan->status)
                                            @case('pending')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">
                                                    Pending
                                                </span>
                                                @break
                                            @case('approved')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">
                                                    Disetujui
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded">
                                                    Ditolak
                                                </span>
                                                @break
                                        @endswitch
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div>
                            <h3 class="flex items-center mb-3 font-medium text-gray-900">
                                <i class="mr-2 text-blue-500 fas fa-box"></i>
                                Informasi Barang
                            </h3>
                            <div class="space-y-2">
                                <p class="flex items-center text-sm text-gray-600">
                                    <i class="w-5 mr-2 text-gray-400 fas fa-box"></i>
                                    <span class="font-medium">Nama Barang:</span>
                                    <span class="ml-2">{{ $pengambilan->barang->nama_barang }}</span>
                                </p>
                                <p class="flex items-center text-sm text-gray-600">
                                    <i class="w-5 mr-2 text-gray-400 fas fa-tags"></i>
                                    <span class="font-medium">Jenis:</span>
                                    <span class="ml-2">
                                        @switch($pengambilan->barang->jenis)
                                            @case('atk')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded">ATK</span>
                                                @break
                                            @case('cetak')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">Cetakan</span>
                                                @break
                                            @case('tinta')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-purple-800 bg-purple-100 rounded">Tinta</span>
                                                @break
                                        @endswitch
                                    </span>
                                </p>
                                <p class="flex items-center text-sm text-gray-600">
                                    <i class="w-5 mr-2 text-gray-400 fas fa-cubes"></i>
                                    <span class="font-medium">Jumlah:</span>
                                    <span class="ml-2">{{ $pengambilan->jumlah }} {{ $pengambilan->barang->satuan }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($pengambilan->keterangan)
                    <div class="p-4 mb-4 border rounded-lg bg-gray-50">
                        <h3 class="flex items-center mb-3 font-medium text-gray-900">
                            <i class="mr-2 text-blue-500 fas fa-sticky-note"></i>
                            Keterangan
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ $pengambilan->keterangan }}
                        </p>
                    </div>
                @endif

                <div class="flex justify-end gap-2">
                    @if($pengambilan->status === 'pending')
                        <button onclick="updateStatus({{ $pengambilan->id }}, 'approved')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition duration-150 bg-green-600 rounded-md hover:bg-green-700">
                            <i class="mr-2 fas fa-check"></i>
                            Setujui
                        </button>
                        <button onclick="updateStatus({{ $pengambilan->id }}, 'rejected')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition duration-150 bg-red-600 rounded-md hover:bg-red-700">
                            <i class="mr-2 fas fa-times"></i>
                            Tolak
                        </button>
                    @else
                        <button onclick="updateStatus({{ $pengambilan->id }}, 'pending')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition duration-150 bg-yellow-600 rounded-md hover:bg-yellow-700">
                            <i class="mr-2 fas fa-undo"></i>
                            Reset Status
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    window.updateStatus = function(id, status) {
        const statusText = {
            'approved': 'menyetujui',
            'rejected': 'menolak',
            'pending': 'mereset status'
        };

        const statusColors = {
            'approved': '#16a34a',
            'rejected': '#dc2626',
            'pending': '#f59e0b'
        };

        const statusIcons = {
            'approved': 'check',
            'rejected': 'times',
            'pending': 'undo'
        };

        Swal.fire({
            title: `${status === 'pending' ? 'Reset Status' : (status === 'approved' ? 'Setujui Pengambilan' : 'Tolak Pengambilan')}?`,
            text: `Anda yakin ingin ${statusText[status]} pengambilan ini?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: statusColors[status],
            cancelButtonColor: '#6b7280',
            confirmButtonText: `<i class="mr-2 fas fa-${statusIcons[status]}"></i>${status === 'pending' ? 'Reset' : (status === 'approved' ? 'Setujui' : 'Tolak')}`,
            cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang memperbarui status pengambilan',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send update request
                fetch(`/admin/pengambilan/${id}/status`, {
                    method: 'PUT',
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
                            text: data.message || 'Status pengambilan berhasil diperbarui',
                            icon: 'success',
                            confirmButtonColor: '#16a34a',
                            confirmButtonText: '<i class="mr-2 fas fa-check"></i>OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan saat memperbarui status');
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
