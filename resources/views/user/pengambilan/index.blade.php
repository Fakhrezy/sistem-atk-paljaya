@extends('layouts.user')

@section('title', 'Pengambilan Barang')

@section('header')
    Pengambilan Barang
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-full">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 w-full">
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Daftar Barang Tersedia</h2>
                            <p class="mt-1 text-sm text-gray-600">Pilih barang untuk pengambilan langsung</p>
                        </div>
                    </div>
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

                <!-- Search and Filter -->
                <div class="mb-6">
                    <form action="{{ route('user.pengambilan.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 12) }}">

                        <!-- Search Input -->
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama barang atau jenis..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Jenis Filter -->
                        <div class="w-full sm:w-48">
                            <select name="jenis" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Jenis</option>
                                @foreach($jenisBarang as $jenis)
                                    <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                                        {{ ucfirst($jenis) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search Button -->
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari
                        </button>

                        @if(request('search') || request('jenis'))
                            <a href="{{ route('user.pengambilan.index', ['per_page' => request('per_page', 12)]) }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Items Grid -->
                @if($barang->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
                        @foreach($barang as $item)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                                <!-- Item Image -->
                                <div class="aspect-w-1 aspect-h-1 w-full h-48 bg-gray-200 rounded-t-lg overflow-hidden">
                                    @if($item->foto)
                                        <div class="flex justify-center items-center h-full p-4">
                                            <img src="{{ asset('storage/'.$item->foto) }}"
                                                 alt="{{ $item->nama_barang }}"
                                                 style="width: 140px; height: 140px; object-fit: cover; border-radius: 0.375rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center h-full bg-gray-100">
                                            <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-600">
                                                <svg class="mr-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                No Image
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Item Info -->
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $item->nama_barang }}</h3>

                                    <div class="space-y-2 mb-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Jenis:</span>
                                            <span class="text-sm font-medium text-gray-900">{{ ucfirst($item->jenis) }}</span>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Satuan:</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $item->satuan }}</span>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Stok Tersedia:</span>
                                            <span class="text-sm font-bold
                                                @if($item->stok > 10) text-green-600
                                                @elseif($item->stok > 5) text-yellow-600
                                                @else text-red-600
                                                @endif">
                                                {{ $item->stok }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    @if($item->stok > 0)
                                        <a href="{{ route('user.pengambilan.create', ['barang_id' => $item->id_barang]) }}"
                                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            Ambil Barang
                                        </a>
                                    @else
                                        <button disabled
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest cursor-not-allowed">
                                            Stok Habis
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        <div class="flex items-center space-x-2 mb-4">
                            <span class="text-sm text-gray-700">Tampilkan</span>
                            <select name="per_page"
                                    onchange="window.location.href = '{{ route('user.pengambilan.index') }}?per_page=' + this.value + '&search={{ request('search') }}&jenis={{ request('jenis') }}'"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                @foreach([12, 24, 36, 48] as $perPage)
                                    <option value="{{ $perPage }}" {{ request('per_page', 12) == $perPage ? 'selected' : '' }}>
                                        {{ $perPage }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-sm text-gray-700">item per halaman</span>
                        </div>
                        <div>
                            {{ $barang->appends(['per_page' => request('per_page'), 'search' => request('search'), 'jenis' => request('jenis')])->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada barang tersedia</h3>
                        <p class="mt-2 text-gray-500">
                            @if(request('search') || request('jenis'))
                                Tidak ditemukan barang yang sesuai dengan pencarian Anda.
                            @else
                                Saat ini tidak ada barang yang tersedia untuk diambil.
                            @endif
                        </p>
                        @if(request('search') || request('jenis'))
                            <div class="mt-4">
                                <a href="{{ route('user.pengambilan.index') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Lihat Semua Barang
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

@endsection
