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
                    <form action="{{ route('admin.monitoring-pengadaan.index') }}" method="GET"
                        class="flex flex-wrap items-end gap-4">
                        <div class="flex-1 min-w-64">
                            <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Cari nama barang...">
                        </div>

                        <div class="min-w-48">
                            <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis Barang</label>
                            <select name="jenis" id="jenis"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Jenis</option>
                                <option value="atk" {{ request('jenis')==='atk' ? 'selected' : '' }}>ATK</option>
                                <option value="cetak" {{ request('jenis')==='cetak' ? 'selected' : '' }}>Cetakan
                                </option>
                                <option value="tinta" {{ request('jenis')==='tinta' ? 'selected' : '' }}>Tinta</option>
                            </select>
                        </div>

                        <div class="min-w-48">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Status</option>
                                <option value="proses" {{ request('status')==='proses' ? 'selected' : '' }}>Proses
                                </option>
                                <option value="selesai" {{ request('status')==='selesai' ? 'selected' : '' }}>Selesai
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
                            @if(request('search') || request('jenis') || request('status'))
                            <a href="{{ route('admin.monitoring-pengadaan.index') }}"
                                class="inline-flex items-center px-4 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md h-9 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                                <th
                                    class="w-12 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                    No</th>
                                <th
                                    class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border w-24">
                                    Tanggal</th>
                                <th
                                    class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border w-48">
                                    Barang</th>
                                <th
                                    class="w-24 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                    Jenis</th>
                                <th
                                    class="w-16 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                    Satuan</th>
                                <th
                                    class="w-16 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                    Saldo</th>
                                <th
                                    class="w-16 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                    Debit</th>
                                <th
                                    class="w-20 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                    Saldo Akhir</th>
                                <th
                                    class="w-24 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                    Status</th>
                                <th
                                    class="w-32 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                    Keterangan</th>
                                <th
                                    class="w-28 px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pengadaans as $index => $pengadaan)
                            <tr class="transition-colors duration-200 hover:bg-gray-50">
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    {{ $pengadaan->tanggal->format('d/m/Y') }}
                                </td>
                                <td class="px-3 py-3 text-sm font-medium text-gray-900 border break-words">
                                    {{ $pengadaan->barang->nama_barang }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    @switch($pengadaan->barang->jenis)
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
                                    {{ ucfirst($pengadaan->barang->jenis) }}
                                    @endswitch
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    {{ $pengadaan->barang->satuan }}
                                </td>
                                <td class="px-3 py-3 text-sm text-right text-gray-900 border">
                                    <span class="font-medium text-blue-600">
                                        {{ number_format($pengadaan->saldo ?? $pengadaan->barang->stok, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-sm text-right text-gray-900 border">
                                    {{ $pengadaan->debit }}
                                </td>
                                <td class="px-3 py-3 text-sm text-right border">
                                    <span class="font-medium text-gray-900">
                                        {{ number_format($pengadaan->saldo_akhir ?? $pengadaan->barang->stok, 0, ',',
                                        '.') }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-sm border">
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded
                                        {{ $pengadaan->status === 'proses' ? 'text-yellow-800 bg-yellow-100' : 'text-green-800 bg-green-100' }}">
                                        {{ $pengadaan->status === 'selesai' ? 'Selesai' : ucfirst($pengadaan->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-500 border">
                                    {{ $pengadaan->keterangan ?: '-' }}
                                </td>
                                <td class="px-3 py-3 text-sm border">
                                    <div class="flex gap-1">
                                        @if($pengadaan->status === 'proses')
                                        <button onclick="updateStatus({{ $pengadaan->id }}, 'selesai')"
                                            class="px-2 py-1 text-xs text-white transition duration-150 bg-green-600 rounded hover:bg-green-700"
                                            title="Selesaikan">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @else
                                        <button onclick="updateStatus({{ $pengadaan->id }}, 'proses')"
                                            class="px-2 py-1 text-xs text-white transition duration-150 bg-yellow-600 rounded hover:bg-yellow-700"
                                            title="Kembalikan ke Proses">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        @endif
                                        @if($pengadaan->status === 'proses')
                                        <button onclick="editPengadaan({{ $pengadaan->id }})"
                                            class="px-2 py-1 text-xs text-white transition duration-150 bg-blue-600 rounded hover:bg-blue-700"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @else
                                        <button disabled
                                            class="px-2 py-1 text-xs text-gray-400 transition duration-150 bg-gray-300 rounded cursor-not-allowed"
                                            title="Tidak dapat mengedit data yang sudah selesai">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endif
                                        <button onclick="deletePengadaan({{ $pengadaan->id }})"
                                            class="px-2 py-1 text-xs text-white transition duration-150 bg-gray-500 rounded hover:bg-gray-600"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-3 py-8 text-center text-gray-500 border">
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
    window.deletePengadaan = function(id) {
        Swal.fire({
            title: 'Hapus Data Pengadaan?',
            text: 'Anda yakin ingin menghapus data pengadaan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="mr-2 fas fa-trash"></i>Hapus!',
            cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus data pengadaan',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send delete request
                fetch(`/admin/monitoring-pengadaan/${id}`, {
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
                            text: data.message || 'Data pengadaan berhasil dihapus',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan saat menghapus data');
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

    window.editPengadaan = function(id) {
        // Fetch current data
        fetch(`/admin/monitoring-pengadaan/${id}/edit`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const pengadaan = data.data;

                Swal.fire({
                    title: 'Edit Data Pengadaan',
                    html: `
                        <div class="text-left">
                            <div class="p-3 mb-4 rounded-md bg-gray-50">
                                <h4 class="flex items-center mb-3 font-medium text-gray-800">
                                    <i class="mr-2 text-blue-500 fas fa-info-circle"></i>
                                    Informasi Pengadaan
                                </h4>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <p class="flex items-center">
                                        <i class="w-4 mr-2 text-gray-500 fas fa-box"></i>
                                        <strong>Nama Barang:</strong> <span class="ml-2">${pengadaan.barang.nama_barang}</span>
                                    </p>
                                    <p class="flex items-center">
                                        <i class="w-4 mr-2 text-gray-500 fas fa-tags"></i>
                                        <strong>Jenis:</strong> <span class="ml-2">${pengadaan.barang.jenis.toUpperCase()}</span>
                                    </p>
                                    <p class="flex items-center">
                                        <i class="w-4 mr-2 text-gray-500 fas fa-calendar-alt"></i>
                                        <strong>Tanggal:</strong> <span class="ml-2">${new Date(pengadaan.tanggal).toLocaleDateString('id-ID')}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="flex items-center block mb-2 text-sm font-medium text-gray-700">
                                    <i class="mr-2 text-green-500 fas fa-plus-circle"></i>
                                    Debit <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="edit_debit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="${pengadaan.debit}" min="1" step="1" placeholder="Masukkan debit">
                            </div>
                            <div class="mb-4">
                                <label class="flex items-center block mb-2 text-sm font-medium text-gray-700">
                                    <i class="mr-2 text-blue-500 fas fa-sticky-note"></i>
                                    Keterangan
                                </label>
                                <textarea id="edit_keterangan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Masukkan keterangan (opsional)">${pengadaan.keterangan || ''}</textarea>
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
                        const debit = document.getElementById('edit_debit').value;
                        const keterangan = document.getElementById('edit_keterangan').value;

                        if (!debit || debit < 1) {
                            Swal.showValidationMessage('Debit harus diisi dan minimal 1!');
                            return false;
                        }

                        return {
                            debit: parseInt(debit),
                            keterangan: keterangan
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Sedang menyimpan perubahan data',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Send update request
                        fetch(`/admin/monitoring-pengadaan/${id}`, {
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
                                    text: data.message || 'Data pengadaan berhasil diperbarui',
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
                                    text: data.message || 'Tidak dapat memperbarui data pengadaan',
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
                                text: 'Terjadi kesalahan saat memperbarui data',
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
                    text: data.message || 'Tidak dapat mengambil data pengadaan',
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
    };

    window.updateStatus = function(id, status) {
        const confirmTitle = status === 'selesai' ? 'Selesaikan Pengadaan?' : 'Kembalikan ke Proses?';
    const confirmText = status === 'selesai' ?
        'Yakin ingin menyelesaikan pengadaan ini? Stok dan saldo barang akan ditambahkan sesuai jumlah debit.' :
        'Yakin ingin mengembalikan status pengadaan ke proses? Stok dan saldo barang akan dikurangi sesuai jumlah debit.';
    const confirmButtonText = status === 'selesai' ? '<i class="mr-2 fas fa-check"></i>Selesaikan!' : '<i class="mr-2 fas fa-undo"></i>Kembalikan!';
    const confirmButtonColor = status === 'selesai' ? '#16a34a' : '#f59e0b';

    Swal.fire({
        title: confirmTitle,
        text: confirmText,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: '#6b7280',
        confirmButtonText: confirmButtonText,
        cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
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
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
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