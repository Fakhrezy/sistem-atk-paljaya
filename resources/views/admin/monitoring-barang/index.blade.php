@extends('layouts.admin')

@section('title', 'Monitoring Barang')

@section('header')
    SISTEM INFORMASI MONITORING BARANG ATK, CETAKAN & TINTA
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-full">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Monitoring Barang</h2>
                            <p class="mt-1 text-sm text-gray-600">Kelola dan monitor pengambilan barang ATK, cetakan, dan tinta</p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <form method="GET" action="{{ route('admin.monitoring-barang.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-64">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Cari nama barang atau pengambil...">
                        </div>

                        <div class="min-w-48">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Status</option>
                                <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            </select>
                        </div>

                        <div class="min-w-48">
                            <label for="bidang" class="block text-sm font-medium text-gray-700 mb-1">Bidang</label>
                            <select id="bidang" name="bidang" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Bidang</option>
                                <option value="umum" {{ request('bidang') == 'umum' ? 'selected' : '' }}>Umum</option>
                                <option value="perencanaan" {{ request('bidang') == 'perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                                <option value="keuangan" {{ request('bidang') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                                <option value="operasional" {{ request('bidang') == 'operasional' ? 'selected' : '' }}>Operasional</option>
                                <option value="lainnya" {{ request('bidang') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="min-w-48">
                            <label for="jenis_barang" class="block text-sm font-medium text-gray-700 mb-1">Jenis Barang</label>
                            <select id="jenis_barang" name="jenis_barang" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Jenis</option>
                                <option value="atk" {{ request('jenis_barang') == 'atk' ? 'selected' : '' }}>ATK</option>
                                <option value="cetak" {{ request('jenis_barang') == 'cetak' ? 'selected' : '' }}>Cetakan</option>
                                <option value="tinta" {{ request('jenis_barang') == 'tinta' ? 'selected' : '' }}>Tinta</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition duration-150">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            <a href="{{ route('admin.monitoring-barang.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-medium transition duration-150">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded relative">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded relative">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Data Table -->
                <div class="bg-white rounded-lg shadow">
                    <!-- Mobile view (hidden on larger screens) -->
                    <div class="block md:hidden">
                        <div class="space-y-4 p-4">
                            @forelse ($monitoringBarang as $index => $item)
                            <div class="bg-gray-50 rounded-lg p-4 border">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-medium text-gray-900 text-sm" title="{{ $item->nama_barang }}">{{ Str::limit($item->nama_barang, 30) }}</h3>
                                    @if($item->jenis_barang)
                                        @switch($item->jenis_barang)
                                            @case('atk')
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    ATK
                                                </span>
                                                @break
                                            @case('cetak')
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Cetakan
                                                </span>
                                                @break
                                            @case('tinta')
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                    Tinta
                                                </span>
                                                @break
                                        @endswitch
                                    @endif
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 mb-3">
                                    <div><span class="font-medium">Pengambil:</span> {{ $item->nama_pengambil }}</div>
                                    <div><span class="font-medium">Bidang:</span> {{ ucfirst($item->bidang) }}</div>
                                    <div><span class="font-medium">Tanggal:</span> {{ \Carbon\Carbon::parse($item->tanggal_ambil)->format('d/m/Y') }}</div>
                                    <div><span class="font-medium">Kredit:</span> <span class="text-red-600 font-medium">-{{ number_format($item->kredit, 0, ',', '.') }}</span></div>
                                </div>
                                <div class="flex justify-between items-center">
                                    @if($item->status == 'diajukan')
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Diajukan
                                        </span>
                                        <div class="flex gap-2">
                                            <button onclick="updateStatus({{ $item->id }}, 'diterima')"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition duration-150">
                                                <i class="fas fa-check mr-1"></i>Terima
                                            </button>
                                            <button onclick="deleteMonitoring({{ $item->id }})"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition duration-150">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Diterima
                                        </span>
                                        <div class="flex gap-2">
                                            <button onclick="updateStatus({{ $item->id }}, 'diajukan')"
                                                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-xs transition duration-150">
                                                <i class="fas fa-undo mr-1"></i>Batalkan
                                            </button>
                                            <button onclick="deleteMonitoring({{ $item->id }})"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition duration-150">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-clipboard-list text-3xl text-gray-400 mb-2"></i>
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
                                    <th class="w-12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">No</th>
                                    <th class="w-28 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Tanggal</th>
                                    <th class="w-36 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Nama Barang</th>
                                    <th class="w-20 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Jenis</th>
                                    <th class="w-32 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Pengambil</th>
                                    <th class="w-32 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Bidang</th>
                                    <th class="w-16 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Saldo</th>
                                    <th class="w-16 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Kredit</th>
                                    <th class="w-16 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Saldo Akhir</th>
                                    <th class="w-20 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Status</th>
                                    <th class="w-24 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Aksi</th>
                                </tr>
                            </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($monitoringBarang as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    {{ $monitoringBarang->firstItem() + $index }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    {{ \Carbon\Carbon::parse($item->tanggal_ambil)->format('d/m/Y') }}
                                </td>
                                <td class="px-3 py-3 text-sm font-medium text-gray-900 border truncate" title="{{ $item->nama_barang }}">
                                    {{ $item->nama_barang }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    @if($item->jenis_barang)
                                        @switch($item->jenis_barang)
                                            @case('atk')
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    ATK
                                                </span>
                                                @break
                                            @case('cetak')
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Cetakan
                                                </span>
                                                @break
                                            @case('tinta')
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                    Tinta
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($item->jenis_barang) }}
                                                </span>
                                        @endswitch
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-500">
                                            Tidak diketahui
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border truncate" title="{{ $item->nama_pengambil }}">
                                    <i class="fas fa-user mr-1 text-blue-500 text-xs"></i>
                                    {{ $item->nama_pengambil }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($item->bidang) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border text-right">
                                    {{ number_format($item->saldo, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-sm text-red-600 border text-right font-medium">
                                    -{{ number_format($item->kredit, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 border text-right font-medium">
                                    {{ number_format($item->saldo_akhir, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-sm border">
                                    @if($item->status == 'diajukan')
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Diajukan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Diterima
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm border">
                                    <div class="flex gap-1">
                                        @if($item->status == 'diajukan')
                                            <button onclick="updateStatus({{ $item->id }}, 'diterima')"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs transition duration-150"
                                                    title="Terima">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @else
                                            <button onclick="updateStatus({{ $item->id }}, 'diajukan')"
                                                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-2 py-1 rounded text-xs transition duration-150"
                                                    title="Batalkan">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                        <button onclick="deleteMonitoring({{ $item->id }})"
                                                class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs transition duration-150"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="px-3 py-8 text-center text-gray-500 border">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-clipboard-list text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-base font-medium">Belum ada data monitoring barang</p>
                                        <p class="text-sm">Data akan muncul setelah ada pengambilan barang yang diajukan</p>
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
    const confirmButtonText = status === 'diterima' ? '<i class="fas fa-check mr-2"></i>Ya, Terima!' : '<i class="fas fa-times mr-2"></i>Ya, Batalkan!';
    const confirmButtonColor = status === 'diterima' ? '#16a34a' : '#f59e0b';

    Swal.fire({
        title: confirmTitle,
        text: confirmText,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: '#dc2626',
        confirmButtonText: confirmButtonText,
        cancelButtonText: '<i class="fas fa-ban mr-2"></i>Batal',
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
                        confirmButtonColor: '#16a34a',
                        confirmButtonText: '<i class="fas fa-check mr-2"></i>OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message || 'Tidak dapat memperbarui status',
                        icon: 'error',
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: '<i class="fas fa-times mr-2"></i>OK'
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
                    confirmButtonText: '<i class="fas fa-times mr-2"></i>OK'
                });
            });
        }
    });
}

// Delete monitoring function
function deleteMonitoring(id) {
    Swal.fire({
        title: 'Hapus Data Monitoring?',
        text: 'Apakah Anda yakin ingin menghapus data monitoring ini? Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-ban mr-2"></i>Batal',
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
                        confirmButtonColor: '#16a34a',
                        confirmButtonText: '<i class="fas fa-check mr-2"></i>OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message || 'Tidak dapat menghapus data monitoring',
                        icon: 'error',
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: '<i class="fas fa-times mr-2"></i>OK'
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
                    confirmButtonText: '<i class="fas fa-times mr-2"></i>OK'
                });
            });
        }
    });
}
</script>
@endsection
