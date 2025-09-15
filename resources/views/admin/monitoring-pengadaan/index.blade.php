@extends('layouts.admin')

@section('title', 'Monitoring Pengadaan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Monitoring Pengadaan</h1>
                    <p class="mt-1 text-sm text-gray-600">Kelola dan pantau usulan pengadaan</p>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="mb-6">
                <form action="{{ route('admin.monitoring-pengadaan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="Cari nama barang...">
                    </div>
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis Barang</label>
                        <select name="jenis" id="jenis" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Semua</option>
                            <option value="atk" {{ request('jenis') === 'atk' ? 'selected' : '' }}>ATK</option>
                            <option value="cetak" {{ request('jenis') === 'cetak' ? 'selected' : '' }}>Cetakan</option>
                            <option value="tinta" {{ request('jenis') === 'tinta' ? 'selected' : '' }}>Tinta</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Semua</option>
                            <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="terima" {{ request('status') === 'terima' ? 'selected' : '' }}>Terima</option>
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center h-9 px-4 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        @if(request('search') || request('jenis') || request('status'))
                            <a href="{{ route('admin.monitoring-pengadaan.index') }}" class="inline-flex items-center h-9 px-4 bg-white border border-gray-300 rounded-md font-medium text-xs text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Barang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pengadaans as $index => $pengadaan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pengadaan->tanggal->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $pengadaan->barang->nama_barang }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            @switch($pengadaan->barang->jenis)
                                                @case('atk')
                                                    <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full">
                                                        ATK
                                                    </span>
                                                    @break
                                                @case('cetak')
                                                    <span class="px-3 py-1 text-xs text-green-600 bg-green-100 rounded-full">
                                                        Cetakan
                                                    </span>
                                                    @break
                                                @case('tinta')
                                                    <span class="px-3 py-1 text-xs text-purple-600 bg-purple-100 rounded-full">
                                                        Tinta
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="px-3 py-1 text-xs text-gray-600 bg-gray-100 rounded-full">
                                                        {{ ucfirst($pengadaan->barang->jenis) }}
                                                    </span>
                                            @endswitch
                                        </div>
                                    </div>
                                </td>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $pengadaan->debit }} {{ $pengadaan->barang->satuan }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $pengadaan->status === 'proses' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($pengadaan->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-500">{{ $pengadaan->keterangan ?: '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <select
                                        class="status-select border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-sm"
                                        onchange="updateStatus({{ $pengadaan->id }}, this.value)"
                                        {{ $pengadaan->status === 'terima' ? 'disabled' : '' }}
                                    >
                                        <option value="proses" {{ $pengadaan->status === 'proses' ? 'selected' : '' }}>Proses</option>
                                        <option value="terima" {{ $pengadaan->status === 'terima' ? 'selected' : '' }}>Terima</option>
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Tidak ada data pengadaan
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
<script>
function updateStatus(id, status) {
    Swal.fire({
        title: 'Konfirmasi',
        text: `Apakah Anda yakin ingin mengubah status menjadi ${status}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('status', status);

            fetch(`/admin/monitoring-pengadaan/${id}/status`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan saat mengubah status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        } else {
            // Reset select to previous value
            const select = document.querySelector(`select[onchange*="${id},"]`);
            select.value = select.getAttribute('data-original-value');
        }
    });
}

// Store original values
document.querySelectorAll('.status-select').forEach(select => {
    select.setAttribute('data-original-value', select.value);
});
</script>
@endpush
@endsection
