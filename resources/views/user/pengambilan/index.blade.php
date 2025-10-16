@extends('layouts.user')

@section('title', 'Pengambilan Barang')

@section('header')
Pengambilan Barang
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-full">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="w-full p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Daftar Barang Tersedia</h2>
                            <p class="mt-1 text-sm text-gray-600">Pilih barang untuk melakukan pengambilan</p>
                        </div>
                        <!-- Cart Link -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('user.cart.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="mr-2 fas fa-shopping-cart"></i>
                                Keranjang
                                <span id="cart-count"
                                    class="px-2 py-1 ml-2 text-xs text-white bg-red-500 rounded-full">0</span>
                            </a>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div class="relative p-4 mb-4 text-blue-700 bg-blue-100 border-l-4 border-blue-500 rounded">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="relative p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500 rounded">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif

                <!-- Search and Filter -->
                <div class="mb-6">
                    <form action="{{ route('user.pengambilan.index') }}" method="GET"
                        class="flex flex-col gap-4 sm:flex-row">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 12) }}">

                        <!-- Search Input -->
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama barang atau jenis..."
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Jenis Filter -->
                        <div class="w-full sm:w-48">
                            <select name="jenis"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Jenis</option>
                                @foreach($jenisBarang as $jenis)
                                <option value="{{ $jenis }}" {{ request('jenis')==$jenis ? 'selected' : '' }}>
                                    {{ ucfirst($jenis) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search Button -->
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="mr-2 fas fa-search"></i>
                            Cari
                        </button>

                        @if(request('search') || request('jenis'))
                        <a href="{{ route('user.pengambilan.index', ['per_page' => request('per_page', 12)]) }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Reset
                        </a>
                        @endif
                    </form>
                </div>

                <!-- Items Grid -->
                @if($barang->count() > 0)
                <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($barang as $item)
                    <div
                        class="transition-shadow duration-200 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg">
                        <!-- Item Image -->
                        <div class="w-full h-48 overflow-hidden bg-gray-200 rounded-t-lg aspect-w-1 aspect-h-1">
                            @if($item->foto)
                            <div class="flex items-center justify-center h-full p-4">
                                <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama_barang }}"
                                    style="width: 140px; height: 140px; object-fit: cover; border-radius: 0.375rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
                            </div>
                            @else
                            <div class="flex items-center justify-center h-full bg-gray-100">
                                <span
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-200 rounded-full">
                                    <i class="mr-2 text-gray-400 fas fa-image"></i>
                                    No Image
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Item Info -->
                        <div class="p-4">
                            <h3 class="mb-2 text-lg font-semibold text-gray-900 line-clamp-2">{{ $item->nama_barang }}
                            </h3>

                            <div class="mb-4 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Jenis:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ ucfirst($item->jenis) }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Satuan:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $item->satuan }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Stok Tersedia:</span>
                                    <span id="stock-{{ $item->id_barang }}" class="text-sm font-bold
                                @if($item->available_stock > 10) text-green-600
                                @elseif($item->available_stock > 5) text-yellow-600
                                @else text-red-600
                                @endif">
                                        {{ $item->available_stock }}
                                    </span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            @if($item->available_stock > 0)
                            <button id="add-btn-{{ $item->id_barang }}" data-barang-id="{{ $item->id_barang }}"
                                data-barang-nama="{{ addslashes($item->nama_barang) }}"
                                data-satuan="{{ $item->satuan }}" data-current-stock="{{ $item->available_stock }}"
                                onclick="handleAddToCart(this)"
                                class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="mr-2 fas fa-cart-plus"></i>
                                Ambil Barang
                            </button>
                            @else
                            <button id="add-btn-{{ $item->id_barang }}" data-barang-id="{{ $item->id_barang }}"
                                data-current-stock="0" disabled
                                class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-semibold tracking-widest text-white bg-gray-400 border border-transparent rounded-md cursor-not-allowed">
                                Barang Belum Tersedia
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    <div class="flex items-center mb-4 space-x-2">
                        <span class="text-sm text-gray-700">Tampilkan</span>
                        <select name="per_page"
                            onchange="window.location.href = '{{ route('user.pengambilan.index') }}?per_page=' + this.value + '&search={{ request('search') }}&jenis={{ request('jenis') }}'"
                            class="text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach([12, 24, 36, 48] as $perPage)
                            <option value="{{ $perPage }}" {{ request('per_page', 12)==$perPage ? 'selected' : '' }}>
                                {{ $perPage }}
                            </option>
                            @endforeach
                        </select>
                        <span class="text-sm text-gray-700">item per halaman</span>
                    </div>
                    <div>
                        {{ $barang->appends(['per_page' => request('per_page'), 'search' => request('search'), 'jenis'
                        => request('jenis')])->links() }}
                    </div>
                </div>
                @else
                <div class="py-12 text-center">
                    <i class="mb-4 text-6xl text-gray-400 fas fa-box-open"></i>
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
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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

<!-- Add to Cart Modal -->
<div id="addToCartModal"
    class="fixed inset-0 z-50 items-center justify-center hidden w-full h-full p-4 overflow-y-auto bg-black bg-opacity-50">
    <div class="relative w-full max-w-lg mx-auto my-8 overflow-hidden bg-white rounded-lg shadow-xl">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-blue-600 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">
                    <i class="mr-2 fas fa-cart-plus"></i>
                    Tambah ke Keranjang
                </h3>
                <button onclick="closeModal()" class="text-blue-100 hover:text-white">
                    <i class="text-lg fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="addToCartForm">
                @csrf
                <input type="hidden" id="id_barang" name="id_barang">

                <!-- Nama Barang -->
                <div class="mb-5">
                    <label class="block mb-3 text-sm font-medium text-gray-700">
                        <i class="mr-1 fas fa-box"></i>
                        Nama Barang
                    </label>
                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <p id="barang_nama" class="mb-2 text-base font-medium text-gray-900">Loading...</p>
                        <p class="text-sm text-gray-500">
                            Stok tersedia: <span id="max_stock" class="font-semibold">0</span> <span
                                id="satuan">pcs</span>
                        </p>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="mb-5">
                    <label class="block mb-3 text-sm font-medium text-gray-700">
                        <i class="mr-1 fas fa-calculator"></i>
                        Jumlah yang Diambil
                    </label>
                    <div class="flex items-center justify-center space-x-4">
                        <button type="button" onclick="decreaseQuantity()"
                            class="flex items-center justify-center w-10 h-10 text-gray-700 bg-gray-200 rounded-full hover:bg-gray-300">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" id="quantity" name="quantity" min="1" value="1"
                            class="w-20 h-10 text-lg font-medium text-center border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" onclick="increaseQuantity()"
                            class="flex items-center justify-center w-10 h-10 text-gray-700 bg-gray-200 rounded-full hover:bg-gray-300">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Pengambil -->
                <div class="mb-5">
                    <label for="pengambil" class="block mb-3 text-sm font-medium text-gray-700">
                        <i class="mr-1 fas fa-user"></i>
                        Nama Pengambil <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="pengambil" name="pengambil" required
                        class="w-full h-12 px-3 text-base border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan nama pengambil barang">
                </div>

                <!-- Bidang -->
                <div class="mb-5">
                    <label for="bidang" class="block mb-3 text-sm font-medium text-gray-700">
                        <i class="mr-1 fas fa-building"></i>
                        Bidang <span class="text-red-500">*</span>
                    </label>
                    <select id="bidang" name="bidang" required
                        class="w-full h-12 px-3 text-base border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Bidang --</option>
                        @foreach(\App\Constants\BidangConstants::getBidangList() as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Keterangan -->
                <div class="mb-5">
                    <label for="keterangan" class="block mb-3 text-sm font-medium text-gray-700">
                        <i class="mr-1 fas fa-sticky-note"></i>
                        Keterangan
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full px-3 py-3 text-base border-2 border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tambahkan keterangan jika diperlukan (opsional)"></textarea>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end px-6 py-4 space-x-3 border-t border-gray-200 bg-gray-50">
            <button onclick="closeModal()"
                class="px-6 py-3 text-base font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="mr-2 fas fa-times"></i>
                Batal
            </button>
            <button id="addToCartBtn" onclick="addToCart()"
                class="px-6 py-3 text-base font-medium text-white bg-blue-600 border-2 border-blue-600 rounded-lg hover:bg-blue-700">
                <i class="mr-2 fas fa-cart-plus"></i>
                Tambah ke Keranjang
            </button>
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

    /* Modal styles */
    #addToCartModal.show {
        display: flex !important;
    }

    #addToCartModal {
        display: none;
    }

    /* Custom input number controls */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
</style>

<script>
    // Load cart count when page loads
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();

    // Auto refresh stock every 30 seconds
    setInterval(function() {
        refreshAllStocks();
    }, 30000);
});

// Update cart count
function updateCartCount() {
    fetch('{{ route("user.cart.count") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = data.count;
            }
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
            // Set cart count to 0 if there's an error
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = '0';
            }
        });
}

// Handle add to cart button click
function handleAddToCart(button) {
    const barangId = button.getAttribute('data-barang-id');
    const barangNama = button.getAttribute('data-barang-nama');
    const satuan = button.getAttribute('data-satuan');
    const currentStock = parseInt(button.getAttribute('data-current-stock'));

    showAddToCartModal(barangId, barangNama, satuan, currentStock);
}

// Modal functions
function showAddToCartModal(barangId, namaBarang, satuan, stok) {
    console.log('Opening modal with data:', { barangId, namaBarang, satuan, stok });

    // Check if stock is 0
    if (stok <= 0) {
        alert('Barang belum tersedia. Stok saat ini: 0');
        return;
    }

    document.getElementById('id_barang').value = barangId;
    document.getElementById('barang_nama').textContent = namaBarang;
    document.getElementById('satuan').textContent = satuan;
    document.getElementById('max_stock').textContent = stok;
    document.getElementById('quantity').max = stok;
    document.getElementById('quantity').value = 1;
    document.getElementById('bidang').value = '';
    document.getElementById('keterangan').value = '';
    document.getElementById('pengambil').value = '';

    const modal = document.getElementById('addToCartModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent background scroll
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('addToCartModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto'; // Restore background scroll
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const maxStock = parseInt(document.getElementById('max_stock').textContent);
    const currentValue = parseInt(quantityInput.value);
    if (currentValue < maxStock) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function addToCart() {
    console.log('AddToCart function called');

    const form = document.getElementById('addToCartForm');
    const formData = new FormData(form);
    const addBtn = document.getElementById('addToCartBtn');

    // Check if CSRF token exists
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Error: CSRF token not found. Please refresh the page.');
        return;
    }

    // Validate bidang
    if (!formData.get('bidang')) {
        // Show error state on select
        const bidangSelect = document.getElementById('bidang');
        bidangSelect.classList.add('border-red-500');
        bidangSelect.focus();

        // Create error message
        let errorMsg = document.getElementById('bidang-error');
        if (!errorMsg) {
            errorMsg = document.createElement('p');
            errorMsg.id = 'bidang-error';
            errorMsg.className = 'text-red-500 text-sm mt-1 font-medium';
            errorMsg.textContent = 'Bidang harus dipilih';
            bidangSelect.parentNode.appendChild(errorMsg);
        }

        // Remove error after user selects
        bidangSelect.addEventListener('change', function() {
            if (this.value) {
                this.classList.remove('border-red-500');
                const errorMsg = document.getElementById('bidang-error');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });

        return;
    }

    // Validate pengambil
    if (!formData.get('pengambil')) {
        // Show error state on input
        const pengambilInput = document.getElementById('pengambil');
        pengambilInput.classList.add('border-red-500');
        pengambilInput.focus();

        // Create error message
        let errorMsg = document.getElementById('pengambil-error');
        if (!errorMsg) {
            errorMsg = document.createElement('p');
            errorMsg.id = 'pengambil-error';
            errorMsg.className = 'text-red-500 text-sm mt-1 font-medium';
            errorMsg.textContent = 'Nama pengambil harus diisi';
            pengambilInput.parentNode.appendChild(errorMsg);
        }

        // Remove error after user types
        pengambilInput.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500');
                const errorMsg = document.getElementById('pengambil-error');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });

        return;
    }

    // Log the data being sent
    console.log('Sending cart data:', {
        id_barang: formData.get('id_barang'),
        quantity: formData.get('quantity'),
        bidang: formData.get('bidang'),
        keterangan: formData.get('keterangan'),
        pengambil: formData.get('pengambil'),
        csrf_token: csrfToken.getAttribute('content').substring(0, 10) + '...'
    });

    addBtn.disabled = true;
    addBtn.innerHTML = `
        <i class="mr-2 fas fa-spinner fa-spin"></i>
        Menambahkan...
    `;

    fetch('{{ route("user.cart.add") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            closeModal();
            updateCartCount();

            // Update stock display and button state
            const barangId = formData.get('id_barang');
            const quantity = parseInt(formData.get('quantity'));
            updateStockDisplay(barangId, quantity);

            // Show success message
            showMessage(data.message, 'success');
        } else {
            alert(data.message || 'Terjadi kesalahan saat menambahkan ke keranjang.');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('Terjadi kesalahan saat menambahkan ke keranjang: ' + error.message);
    })
    .finally(() => {
        addBtn.disabled = false;
        addBtn.innerHTML = `
            <i class="mr-2 fas fa-cart-plus"></i>
            Tambah ke Keranjang
        `;
    });
}

// Helper function to show messages
function showMessage(message, type) {
    const alertClass = type === 'success' ? 'bg-blue-100 border-blue-500 text-blue-700' : 'bg-red-100 border-red-500 text-red-700';
    const alertDiv = document.createElement('div');
    alertDiv.className = `mb-4 ${alertClass} border-l-4 p-4 rounded relative`;
    alertDiv.innerHTML = `<span class="block sm:inline">${message}</span>`;

    const contentDiv = document.querySelector('.p-6.text-gray-900');
    if (contentDiv) {
        contentDiv.insertBefore(alertDiv, contentDiv.firstChild);

        // Remove alert after 3 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
}

// Update stock display after adding to cart
function updateStockDisplay(barangId, quantityAdded) {
    const stockElement = document.getElementById('stock-' + barangId);
    const buttonElement = document.getElementById('add-btn-' + barangId);

    if (stockElement && buttonElement) {
        const currentStock = parseInt(stockElement.textContent);
        const newStock = currentStock - quantityAdded;

        // Update stock display
        stockElement.textContent = newStock;

        // Update stock color based on new value
        stockElement.className = stockElement.className.replace(/text-(green|yellow|red)-600/, '');
        if (newStock > 10) {
            stockElement.classList.add('text-green-600');
        } else if (newStock > 5) {
            stockElement.classList.add('text-yellow-600');
        } else if (newStock > 0) {
            stockElement.classList.add('text-red-600');
        } else {
            stockElement.classList.add('text-red-600');
        }

        // Update button data attribute
        buttonElement.setAttribute('data-current-stock', newStock.toString());

        // Update button if stock reaches 0
        if (newStock <= 0) {
            buttonElement.disabled = true;
            buttonElement.onclick = null;
            buttonElement.className = 'w-full inline-flex items-center justify-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest cursor-not-allowed';
            buttonElement.innerHTML = 'Barang Belum Tersedia';
        }
    }
}

// Refresh all stocks from server (auto-refresh every 30 seconds)
function refreshAllStocks() {
    const buttons = document.querySelectorAll('[data-barang-id]');

    buttons.forEach(button => {
        const barangId = button.getAttribute('data-barang-id');

        fetch(`{{ url('/user/pengambilan/stock') }}/${barangId}`)
            .then(response => response.json())
            .then(data => {
                const stockElement = document.getElementById('stock-' + barangId);

                if (stockElement) {
                    const currentDisplayStock = parseInt(stockElement.textContent);
                    const actualStock = data.available_stock;

                    // Only update if there's a difference
                    if (currentDisplayStock !== actualStock) {
                        stockElement.textContent = actualStock;

                        // Update stock color
                        stockElement.className = stockElement.className.replace(/text-(green|yellow|red)-600/, '');
                        if (actualStock > 10) {
                            stockElement.classList.add('text-green-600');
                        } else if (actualStock > 5) {
                            stockElement.classList.add('text-yellow-600');
                        } else {
                            stockElement.classList.add('text-red-600');
                        }

                        // Update button state
                        button.setAttribute('data-current-stock', actualStock.toString());

                        if (actualStock <= 0) {
                            button.disabled = true;
                            button.onclick = null;
                            button.className = 'w-full inline-flex items-center justify-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest cursor-not-allowed';
                            button.innerHTML = 'Barang Belum Tersedia';
                        } else if (button.disabled) {
                            // Re-enable button if it was disabled but now has stock
                            button.disabled = false;
                            button.onclick = function() { handleAddToCart(this); };
                            button.className = 'w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150';
                            button.innerHTML = `<i class="mr-2 fas fa-cart-plus"></i>
                            Ambil Barang`;
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error refreshing stock for item ' + barangId + ':', error);
            });
    });
}

// Close modal when clicking outside or pressing ESC
window.onclick = function(event) {
    const modal = document.getElementById('addToCartModal');
    if (event.target == modal) {
        closeModal();
    }
}

// Close modal with ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('addToCartModal');
        if (modal.classList.contains('show')) {
            closeModal();
        }
    }
});
</script>

@endsection