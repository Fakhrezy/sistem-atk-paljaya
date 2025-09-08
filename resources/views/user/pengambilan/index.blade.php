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
                            <p class="mt-1 text-sm text-gray-600">Pilih barang untuk ditambahkan ke keranjang</p>
                        </div>
                        <!-- Cart Link -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('user.cart.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Keranjang
                                <span id="cart-count" class="ml-2 bg-red-500 text-white rounded-full px-2 py-1 text-xs">0</span>
                            </a>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded relative">
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
                            <i class="fas fa-search mr-2"></i>
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
                                                <i class="fas fa-image mr-2 text-gray-400"></i>
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
                                        <button id="add-btn-{{ $item->id_barang }}"
                                                data-barang-id="{{ $item->id_barang }}"
                                                data-barang-nama="{{ addslashes($item->nama_barang) }}"
                                                data-satuan="{{ $item->satuan }}"
                                                data-current-stock="{{ $item->available_stock }}"
                                                onclick="handleAddToCart(this)"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <i class="fas fa-cart-plus mr-2"></i>
                                            Ambil Barang
                                        </button>
                                    @else
                                        <button id="add-btn-{{ $item->id_barang }}"
                                                data-barang-id="{{ $item->id_barang }}"
                                                data-current-stock="0"
                                                disabled
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest cursor-not-allowed">
                                            Barang Belum Tersedia
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
                        <i class="fas fa-box-open text-6xl text-gray-400 mb-4"></i>
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

<!-- Add to Cart Modal -->
<div id="addToCartModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 items-center justify-center p-4 hidden">
    <div class="relative max-w-lg w-full bg-white rounded-2xl shadow-2xl overflow-hidden">

        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white bg-opacity-20 p-2 rounded-xl">
                        <i class="fas fa-cart-plus text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Tambah Item untuk Diambil</h3>
                </div>
                <button onclick="closeModal()"
                        class="text-white hover:text-gray-200 p-2 rounded-xl hover:bg-white hover:bg-opacity-20 transition-all duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6">
            <form id="addToCartForm">
                @csrf
                <input type="hidden" id="id_barang" name="id_barang">

                <!-- Nama Barang & Jumlah -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Nama Barang & Jumlah</label>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                            <!-- Nama Barang -->
                            <div class="flex-1">
                                <p class="text-lg font-bold text-gray-900 pr-4" id="barang_nama">Loading...</p>
                            </div>

                            <!-- Jumlah Controls -->
                            <div class="flex items-center justify-center sm:justify-end space-x-2">
                                <button type="button" onclick="decreaseQuantity()"
                                        class="w-8 h-8 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105 focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>

                                <input type="number" id="quantity" name="quantity" min="1" value="1"
                                       class="w-16 h-8 text-center text-sm font-bold border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0 bg-white">

                                <button type="button" onclick="increaseQuantity()"
                                        class="w-8 h-8 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105 focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Max Stock Info -->
                        <div class="mt-3 text-center sm:text-right">
                            <p class="text-xs text-gray-500">
                                Max: <span id="max_stock" class="font-semibold text-blue-600">0</span> <span id="satuan" class="font-medium">pcs</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bidang -->
                <div class="space-y-2">
                    <label for="bidang" class="block text-sm font-semibold text-gray-700">Bidang <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="bidang" name="bidang" required
                                class="w-full h-12 px-4 pr-10 text-base border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 bg-white appearance-none">
                            <option value="">-- Pilih Bidang --</option>
                            <option value="umum">Umum</option>
                            <option value="perencanaan">Perencanaan</option>
                            <option value="keuangan">Keuangan</option>
                            <option value="operasional">Operasional</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="space-y-2">
                    <label for="keterangan" class="block text-sm font-semibold text-gray-700">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                              class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 resize-none bg-white"
                              placeholder="Masukkan keterangan jika diperlukan (opsional)"></textarea>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex space-x-3 border-t border-gray-200">
            <button onclick="closeModal()"
                    class="flex-1 h-12 px-4 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                Batal
            </button>
            <button id="addToCartBtn" onclick="addToCart()"
                    class="flex-1 h-12 px-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02] shadow-lg">
                <span class="flex items-center justify-center space-x-2">
                    <i class="fas fa-cart-plus"></i>
                    <span>Tambah ke Keranjang</span>
                </span>
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

/* Modal animation */
#addToCartModal .relative {
    transform: scale(0.9) translateY(-10px);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

#addToCartModal.show .relative {
    transform: scale(1) translateY(0);
    opacity: 1;
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

/* Focus states */
.focus\:ring-0:focus {
    box-shadow: none !important;
}

/* Select dropdown arrow */
select {
    background-image: none;
}

/* Responsive modal */
@media (max-width: 640px) {
    #addToCartModal .relative {
        max-width: 95vw;
        margin: 0.5rem;
    }

    /* Stack quantity controls vertically on very small screens */
    @media (max-width: 480px) {
        #addToCartModal .flex-col {
            flex-direction: column;
        }

        #addToCartModal .space-x-2 {
            flex-direction: row;
            justify-content: center;
        }
    }
}

/* Button hover effects */
button:hover {
    transform: translateY(-1px);
}

#addToCartBtn:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Loading state for button */
#addToCartBtn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}
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

    // Log the data being sent
    console.log('Sending cart data:', {
        id_barang: formData.get('id_barang'),
        quantity: formData.get('quantity'),
        bidang: formData.get('bidang'),
        keterangan: formData.get('keterangan'),
        csrf_token: csrfToken.getAttribute('content').substring(0, 10) + '...'
    });

    addBtn.disabled = true;
    addBtn.innerHTML = `
        <span class="flex items-center justify-center space-x-2">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Menambahkan...</span>
        </span>
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
            <span class="flex items-center justify-center space-x-2">
                <i class="fas fa-cart-plus"></i>
                <span>Tambah ke Keranjang</span>
            </span>
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
                            button.innerHTML = `<i class="fas fa-cart-plus mr-2"></i>
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
