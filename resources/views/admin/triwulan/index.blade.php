@extends('layouts.admin')

@section('title', 'Data Triwulan')

@section('header')
SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="h-full">
    <div class="max-w-full">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Data Triwulan</h2>

                        </div>
                    </div>
                </div>

                @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                </script>
                @endif

                <!-- Sinkronkan Data Button -->
                <div class="flex justify-end mb-6">
                    <button onclick="syncAllData()"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest text-green-600 transition duration-150 ease-in-out bg-white border border-green-500 rounded-md shadow-sm hover:bg-green-50 focus:bg-green-50 active:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 hover:shadow">
                        <i class="fas fa-sync-alt w-4 h-4 mr-2"></i>
                        Sinkronkan Data
                    </button>
                </div>

                <!-- Filters -->
                <div class="p-4 mb-6 rounded-lg bg-gray-50">
                    <form action="{{ route('admin.triwulan.index') }}" method="GET"
                        class="flex flex-wrap items-end gap-4">
                        <div class="min-w-48">
                            <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Cari nama barang..."
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div class="min-w-48">
                            <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                            <select name="tahun" id="tahun"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Tahun</option>
                                @foreach($tahuns as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun')==$tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="min-w-48">
                            <label for="triwulan" class="block text-sm font-medium text-gray-700">Triwulan</label>
                            <select name="triwulan" id="triwulan"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Triwulan</option>
                                <option value="1" {{ request('triwulan')=='1' ? 'selected' : '' }}>Triwulan 1</option>
                                <option value="2" {{ request('triwulan')=='2' ? 'selected' : '' }}>Triwulan 2</option>
                                <option value="3" {{ request('triwulan')=='3' ? 'selected' : '' }}>Triwulan 3</option>
                                <option value="4" {{ request('triwulan')=='4' ? 'selected' : '' }}>Triwulan 4</option>
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
                            @if(request('search') || request('tahun') || request('triwulan'))
                            <a href="{{ route('admin.triwulan.index') }}"
                                class="inline-flex items-center px-4 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md h-9 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="w-full overflow-x-auto bg-white rounded-lg shadow-md">
                    <table class="w-full border-collapse table-fixed min-w-max">
                        <thead>
                            <tr class="bg-gray-100">
                                <th
                                    class="w-12 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    No</th>
                                <th
                                    class="w-36 px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">
                                    Periode</th>
                                <th
                                    class="w-56 px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">
                                    Nama Barang</th>
                                <th
                                    class="w-20 px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">
                                    Satuan</th>
                                <th
                                    class="w-24 px-3 py-3 text-xs font-semibold tracking-wider text-right text-gray-900 uppercase border border-gray-300">
                                    Harga Satuan</th>
                                <th
                                    class="w-20 px-3 py-3 text-xs font-semibold tracking-wider text-right text-gray-900 uppercase border border-gray-300">
                                    Saldo Awal</th>
                                <th
                                    class="w-20 px-3 py-3 text-xs font-semibold tracking-wider text-right text-gray-900 uppercase border border-gray-300">
                                    Total Kredit</th>
                                <!-- Hidden: Total Harga Kredit -->
                                <th
                                    class="w-20 px-3 py-3 text-xs font-semibold tracking-wider text-right text-gray-900 uppercase border border-gray-300">
                                    Total Debit</th>
                                <th
                                    class="w-32 px-3 py-3 text-xs font-semibold tracking-wider text-right text-gray-900 uppercase border border-gray-300">
                                    Total Harga Debit</th>
                                <th
                                    class="w-32 px-3 py-3 text-xs font-semibold tracking-wider text-right text-gray-900 uppercase border border-gray-300">
                                    Total Persediaan</th>
                                <th
                                    class="w-36 px-3 py-3 text-xs font-semibold tracking-wider text-right text-gray-900 uppercase border border-gray-300">
                                    Total Harga Persediaan</th>
                                <!-- Hidden: Aksi column -->
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($triwulans as $index => $triwulan)
                            <tr class="transition-colors duration-200 ease-in-out hover:bg-gray-50">
                                <td
                                    class="px-3 py-4 text-sm font-medium text-gray-900 border border-gray-300 whitespace-nowrap text-center align-top">
                                    {{ ($triwulans->currentPage() - 1) * $triwulans->perPage() + $index + 1 }}
                                </td>
                                <td class="px-3 py-4 border border-gray-300 align-top">
                                    <div class="text-sm font-medium text-gray-900 break-words leading-relaxed">{{
                                        $triwulan->nama_triwulan }}</div>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 align-top">
                                    <div class="text-sm font-medium text-gray-900 break-words leading-relaxed">{{
                                        $triwulan->nama_barang }}</div>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap align-top">
                                    <span class="text-sm text-gray-900">{{ $triwulan->satuan }}</span>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap text-right align-top">
                                    <span class="text-sm text-gray-900">Rp {{ number_format($triwulan->harga_satuan, 0,
                                        ',', '.') }}</span>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap text-right align-top">
                                    <span class="text-sm font-medium text-gray-900">{{
                                        number_format($triwulan->saldo_awal_triwulan, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap text-right align-top">
                                    <span class="text-sm font-medium text-red-600">{{
                                        number_format($triwulan->total_kredit_triwulan, 0, ',', '.') }}</span>
                                </td>
                                <!-- Hidden: Total Harga Kredit cell -->
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap text-right align-top">
                                    <span class="text-sm font-medium text-green-600">{{
                                        number_format($triwulan->total_debit_triwulan, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-2 py-4 border border-gray-300 text-right align-top">
                                    <span class="text-xs text-green-600 break-words leading-tight">Rp {{
                                        number_format($triwulan->total_harga_debit, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap text-right align-top">
                                    <span class="text-sm font-medium text-blue-600">{{
                                        number_format($triwulan->total_persediaan_triwulan, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-2 py-4 border border-gray-300 text-right align-top">
                                    <span class="text-xs font-medium text-blue-600 break-words leading-tight">Rp {{
                                        number_format($triwulan->total_harga_persediaan, 0, ',', '.') }}</span>
                                </td>
                                <!-- Hidden: Aksi column -->
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-calendar-alt text-4xl text-gray-400 mb-2"></i>
                                        <p class="text-base font-medium text-gray-500">Belum ada data triwulan</p>
                                        <p class="text-sm text-gray-400 mt-1">Gunakan tombol "Generate Data" untuk
                                            membuat laporan triwulan</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($triwulans->hasPages())
                <div class="mt-4">
                    {{ $triwulans->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for sync all data -->
<form id="syncAllForm" action="{{ route('admin.triwulan.syncall') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    function syncAllData() {
    // Show loading directly without confirmation
    Swal.fire({
        title: 'Menyinkronkan Data...',
        text: 'Sedang memproses sinkronisasi data triwulan',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Submit form directly
    document.getElementById('syncAllForm').submit();
}function deleteTriwulan(id) {
    Swal.fire({
        title: 'Hapus Data Triwulan?',
        text: 'Yakin ingin menghapus data triwulan ini?',
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
                text: 'Sedang menghapus data triwulan',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send delete request
            fetch(`/admin/triwulan/${id}`, {
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
                        text: data.message || 'Data triwulan berhasil dihapus',
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
}
</script>

@endsection
