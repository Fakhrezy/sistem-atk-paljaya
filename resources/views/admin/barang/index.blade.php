@extends('layouts.admin')

@section('title', 'Data Barang')

@section('header')
    SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<script>
function deleteBarang(url) {
    Swal.fire({
        title: 'Hapus Barang?',
        text: 'Yakin ingin menghapus barang ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6B7280',
        cancelButtonColor: '#dc2626',
        confirmButtonText: '<i class="mr-2 fas fa-trash"></i>Hapus!',
        cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Menghapus Barang...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);

            // Add method override
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

    <div class="h-full">
        <div class="max-w-full">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="w-full p-6 text-gray-900">
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-800">Daftar Barang</h2>

                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="relative p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500 rounded">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1 max-w-2xl">
                            <form action="{{ route('admin.barang') }}" method="GET" class="flex gap-2">
                                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                                <div class="flex-1">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                           placeholder="Cari nama barang..."
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div class="w-44">
                                    <select name="jenis" class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <option value="">Semua Jenis</option>
                                        <option value="atk" {{ request('jenis') == 'atk' ? 'selected' : '' }}>ATK</option>
                                        <option value="cetak" {{ request('jenis') == 'cetak' ? 'selected' : '' }}>Cetak</option>
                                        <option value="tinta" {{ request('jenis') == 'tinta' ? 'selected' : '' }}>Tinta</option>
                                    </select>
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                                @if(request('search') || request('jenis'))
                                    <a href="{{ route('admin.barang', ['per_page' => request('per_page', 10)]) }}"
                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Reset
                                    </a>
                                @endif
                            </form>
                        </div>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.barang.export') }}{{ request()->has('search') || request()->has('jenis') ? '?' . http_build_query(request()->all()) : '' }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest transition duration-150 ease-in-out bg-white border rounded-md shadow-sm border-emerald-500 text-emerald-600 hover:bg-emerald-50 focus:bg-emerald-50 active:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export Excel
                            </a>

                            <a href="{{ route('admin.barang.print') }}{{ request()->has('search') || request()->has('jenis') ? '?' . http_build_query(request()->all()) : '' }}"
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest text-gray-600 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print
                            </a>

                            <a href="{{ route('admin.barang.create') }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest text-green-600 transition duration-150 ease-in-out bg-white border border-green-500 rounded-md shadow-sm hover:bg-green-50 focus:bg-green-50 active:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Barang
                            </a>
                        </div>
                    </div>

                    <div class="w-full overflow-x-auto bg-white rounded-lg shadow-md">
                        <table class="w-full border-collapse table-fixed min-w-max">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300 w-28">ID Barang</th>
                                    <th scope="col" class="w-48 px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Nama Barang</th>
                                    <th scope="col" class="w-20 px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Satuan</th>
                                    <th scope="col" class="w-32 px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Harga</th>
                                    <th scope="col" class="w-16 px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Stok</th>
                                    <th scope="col" class="w-24 px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Jenis</th>
                                    <th scope="col" class="w-32 px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Foto</th>
                                    <th scope="col" class="w-20 px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($barang as $item)
                                <tr class="transition-colors duration-200 ease-in-out hover:bg-gray-50">
                                                                        <td class="px-3 py-4 text-sm font-medium text-gray-900 border border-gray-300 whitespace-nowrap">
                                        <span class="font-mono text-sm">{{ $item->id_barang }}</span>
                                    </td>
                                    <td class="px-3 py-4 border border-gray-300 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_barang }}</div>
                                    </td>
                                    <td class="px-3 py-4 border border-gray-300 whitespace-nowrap">
                                        <span class="px-2 py-1 text-sm text-gray-600 bg-gray-100 rounded-full">{{ $item->satuan }}</span>
                                    </td>
                                    <td class="px-3 py-4 border border-gray-300 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-green-600">
                                            Rp {{ number_format($item->harga_barang, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 border border-gray-300 whitespace-nowrap">
                                        <span class="text-sm font-medium {{ $item->stok > 10 ? 'text-green-600' : ($item->stok > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $item->stok }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 border border-gray-300 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                            {{ ucfirst($item->jenis) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 border border-gray-300 whitespace-nowrap">
                                        @if($item->foto)
                                            <div class="flex justify-center p-1">
                                                <img src="{{ asset('storage/'.$item->foto) }}"
                                                     alt="{{ $item->nama_barang }}"
                                                     style="width: 90px; height: 90px; object-fit: cover; border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <svg class="mr-1.5 h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                No Image
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 text-sm font-medium border border-gray-300 whitespace-nowrap">
                                        <div class="flex gap-1">
                                            <a href="{{ route('admin.barang.edit', $item) }}"
                                               class="px-2 py-1 text-xs text-white transition duration-150 bg-blue-600 rounded hover:bg-blue-700"
                                               title="Edit Barang">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteBarang('{{ route('admin.barang.destroy', $item) }}')"
                                                    class="px-2 py-1 text-xs text-white transition duration-150 bg-gray-500 rounded hover:bg-gray-600"
                                                    title="Hapus Barang">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="mt-2 text-base font-medium text-gray-500">Belum ada data barang</p>
                                            <p class="mt-1 text-sm text-gray-400">Silakan tambahkan barang baru menggunakan tombol di atas</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        <div class="flex items-center mb-4 space-x-2">
                            <span class="text-sm text-gray-700">Tampilkan</span>
                            <select name="per_page"
                                    onchange="window.location.href = '{{ route('admin.barang') }}?per_page=' + this.value + '&search={{ request('search') }}&jenis={{ request('jenis') }}'"
                                    class="text-sm border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                @foreach([10, 25, 50, 100] as $perPage)
                                    <option value="{{ $perPage }}" {{ request('per_page', 10) == $perPage ? 'selected' : '' }}>
                                        {{ $perPage }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-sm text-gray-700">item per halaman</span>
                        </div>
                        <div>
                            {{ $barang->appends(['per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
