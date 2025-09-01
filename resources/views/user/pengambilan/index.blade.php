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
                            <p class="mt-1 text-sm text-gray-600">Tambahkan barang ke keranjang untuk pengambilan</p>
                        </div>
                        <div>
                            <a href="{{ route('user.cart.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8.5m-8.5 0L12 21"/>
                                </svg>
                                Keranjang
                                <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                            </a>
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
                                        <button onclick="addToCart('{{ $item->id_barang }}', '{{ $item->nama_barang }}')"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8.5m-8.5 0L12 21"/>
                                            </svg>
                                            Tambah ke Keranjang
                                        </button>
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

<!-- Add to Cart Modal -->
<div id="addToCartModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">Tambah ke Keranjang</h3>
            <div class="mt-4">
                <form id="addToCartForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <input type="number" id="cartQuantity" min="1" value="1"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bidang</label>
                        <input type="text" id="cartBidang" value="{{ auth()->user()->bidang ?? '' }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea id="cartKeterangan" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeAddToCartModal()"
                                class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let currentItemId = null;
let currentItemName = null;

// Load cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});

function addToCart(itemId, itemName) {
    currentItemId = itemId;
    currentItemName = itemName;

    // Reset form
    document.getElementById('cartQuantity').value = 1;
    document.getElementById('cartKeterangan').value = '';

    // Show modal
    document.getElementById('addToCartModal').classList.remove('hidden');
}

function closeAddToCartModal() {
    document.getElementById('addToCartModal').classList.add('hidden');
    currentItemId = null;
    currentItemName = null;
}

document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const quantity = document.getElementById('cartQuantity').value;
    const bidang = document.getElementById('cartBidang').value;
    const keterangan = document.getElementById('cartKeterangan').value;

    if (!bidang.trim()) {
        alert('Bidang harus diisi');
        return;
    }

    // Send request to add to cart
    fetch('/user/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id_barang: currentItemId,
            quantity: parseInt(quantity),
            bidang: bidang,
            keterangan: keterangan
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`${currentItemName} berhasil ditambahkan ke keranjang!`);
            updateCartCount();
            closeAddToCartModal();
        } else {
            alert(data.message || 'Terjadi kesalahan saat menambahkan ke keranjang');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan ke keranjang');
    });
});

function updateCartCount() {
    fetch('/user/cart/count')
    .then(response => response.json())
    .then(data => {
        const cartBadge = document.getElementById('cart-count');
        if (cartBadge) {
            cartBadge.textContent = data.count;
            cartBadge.style.display = data.count > 0 ? 'inline' : 'none';
        }
    })
    .catch(error => console.error('Error updating cart count:', error));
}

// Close modal when clicking outside
document.getElementById('addToCartModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddToCartModal();
    }
});
</script>
@endsection
