@extends('layouts.admin')

@section('title', 'Data Barang')

@section('header')
    SISTEM INFORMASI MONITORING BARANG ATK, CETAKAN & TINTA
@endsection

@section('content')

    <div class="h-full">
        <div class="max-w-full">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 w-full">
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-800">Daftar Barang</h2>
                                <p class="mt-1 text-sm text-gray-600">Kelola data barang ATK, Cetak, dan Tinta</p>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded relative">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Total Barang -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-blue-100 truncate">Total Barang</dt>
                                            <dd class="text-lg font-medium text-white">{{ $stats['total'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ATK -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-green-100 truncate">ATK</dt>
                                            <dd class="text-lg font-medium text-white">{{ $stats['atk'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cetak -->
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-yellow-100 truncate">Cetak</dt>
                                            <dd class="text-lg font-medium text-white">{{ $stats['cetak'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tinta -->
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17v4a2 2 0 002 2h4M15 8l-5 5"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-purple-100 truncate">Tinta</dt>
                                            <dd class="text-lg font-medium text-white">{{ $stats['tinta'] }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 flex justify-between items-center">
                        <div class="flex-1 max-w-2xl">
                            <form action="{{ route('admin.barang') }}" method="GET" class="flex gap-2">
                                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                                <div class="flex-1">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                           placeholder="Cari nama barang..."
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div class="w-44">
                                    <select name="jenis" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <option value="">Semua Jenis</option>
                                        <option value="atk" {{ request('jenis') == 'atk' ? 'selected' : '' }}>ATK</option>
                                        <option value="cetak" {{ request('jenis') == 'cetak' ? 'selected' : '' }}>Cetak</option>
                                        <option value="tinta" {{ request('jenis') == 'tinta' ? 'selected' : '' }}>Tinta</option>
                                    </select>
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                                @if(request('search') || request('jenis'))
                                    <a href="{{ route('admin.barang', ['per_page' => request('per_page', 10)]) }}"
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Reset
                                    </a>
                                @endif
                            </form>
                        </div>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.barang.export') }}{{ request()->has('search') || request()->has('jenis') ? '?' . http_build_query(request()->all()) : '' }}"
                               class="inline-flex items-center px-4 py-2 bg-white border border-emerald-500 rounded-md font-semibold text-sm text-emerald-600 tracking-widest hover:bg-emerald-50 focus:bg-emerald-50 active:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export Excel
                            </a>

                            <a href="{{ route('admin.barang.print') }}{{ request()->has('search') || request()->has('jenis') ? '?' . http_build_query(request()->all()) : '' }}"
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-600 tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print
                            </a>

                            <a href="{{ route('admin.barang.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-white border border-green-500 rounded-md font-semibold text-sm text-green-600 tracking-widest hover:bg-green-50 focus:bg-green-50 active:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Barang
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md w-full overflow-x-auto">
                        <table class="w-full table-fixed border-collapse min-w-max">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th scope="col" class="w-[10%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">ID Barang</th>
                                    <th scope="col" class="w-[16%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Nama Barang</th>
                                    <th scope="col" class="w-[8%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Satuan</th>
                                    <th scope="col" class="w-[12%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Harga</th>
                                    <th scope="col" class="w-[8%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Stok</th>
                                    <th scope="col" class="w-[10%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Jenis</th>
                                    <th scope="col" class="w-[16%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Foto</th>
                                    <th scope="col" class="w-[20%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($barang as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-200 ease-in-out">
                                                                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-300">
                                        <span class="font-mono text-sm">{{ $item->id_barang }}</span>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap border border-gray-300">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_barang }}</div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap border border-gray-300">
                                        <span class="px-2 py-1 text-sm text-gray-600 bg-gray-100 rounded-full">{{ $item->satuan }}</span>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap border border-gray-300">
                                        <span class="text-sm font-semibold text-green-600">
                                            Rp {{ number_format($item->harga_barang, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap border border-gray-300">
                                        <span class="text-sm font-medium {{ $item->stok > 10 ? 'text-green-600' : ($item->stok > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $item->stok }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap border border-gray-300">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($item->jenis) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap border border-gray-300">
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
                                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium border border-gray-300">
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('admin.barang.edit', $item) }}"
                                               class="group inline-flex items-center px-3 py-1.5 border border-indigo-300 text-indigo-600 hover:bg-indigo-50 rounded-md text-sm font-medium transition-colors duration-150 ease-in-out">
                                                <svg class="w-4 h-4 mr-2 text-indigo-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')"
                                                  action="{{ route('admin.barang.destroy', $item) }}"
                                                  method="POST"
                                                  class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="group inline-flex items-center px-3 py-1.5 border border-red-300 text-red-600 hover:bg-red-50 rounded-md text-sm font-medium transition-colors duration-150 ease-in-out">
                                                    <svg class="w-4 h-4 mr-2 text-red-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
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
                                            <p class="mt-2 text-gray-500 text-base font-medium">Belum ada data barang</p>
                                            <p class="mt-1 text-gray-400 text-sm">Silakan tambahkan barang baru menggunakan tombol di atas</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        <div class="flex items-center space-x-2 mb-4">
                            <span class="text-sm text-gray-700">Tampilkan</span>
                            <select name="per_page"
                                    onchange="window.location.href = '{{ route('admin.barang') }}?per_page=' + this.value + '&search={{ request('search') }}&jenis={{ request('jenis') }}'"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
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
