@extends('layouts.admin')

@section('title', 'Pengambilan Barang')

@section('header')
SISTEM MONITORING BARANG HABIS PAKAI
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
                            <a href="{{ route('admin.cart.index') }}"
                                class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-900">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Keranjang
                                <span id="cart-count"
                                    class="ml-2 rounded-full bg-red-500 px-2 py-1 text-xs text-white">0</span>
                            </a>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
				            Swal.fire({
				                icon: 'success',
				                title: 'Berhasil!',
				                text: '{{ session('success') }}',
				                showConfirmButton: false,
				                timer: 2000,
				                timerProgressBar: true,
				                toast: true,
				                position: 'top-end'
				            });
				        });
                </script>
                @endif

                @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
				            Swal.fire({
				                icon: 'error',
				                title: 'Error!',
				                text: '{{ session('error') }}',
				                confirmButtonColor: '#d33'
				            });
				        });
                </script>
                @endif

                <!-- Search and Filter -->
                <div class="mb-6">
                    <form action="{{ route('admin.pengambilan.index') }}" method="GET"
                        class="flex flex-col gap-4 sm:flex-row">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 12) }}">

                        <!-- Search Input -->
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama barang atau jenis..."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Jenis Filter -->
                        <div class="w-full sm:w-48">
                            <select name="jenis"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Jenis</option>
                                @foreach ($jenisBarang as $jenis)
                                <option value="{{ $jenis }}" {{ request('jenis')==$jenis ? 'selected' : '' }}>
                                    {{ ucfirst($jenis) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search Button -->
                        <button type="submit"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-search mr-2"></i>
                            Cari
                        </button>

                        @if (request('search') || request('jenis'))
                        <a href="{{ route('admin.pengambilan.index', ['per_page' => request('per_page', 12)]) }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Reset
                        </a>
                        @endif
                    </form>
                </div>

                <!-- Items Grid -->
                @if ($barang->count() > 0)
                <div class="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($barang as $item)
                    <div
                        class="rounded-lg border border-gray-200 bg-white shadow-md transition-shadow duration-200 hover:shadow-lg">
                        <!-- Item Image -->
                        <div class="aspect-w-1 aspect-h-1 h-48 w-full overflow-hidden rounded-t-lg bg-gray-200">
                            @if ($item->foto)
                            <div class="flex h-full items-center justify-center p-4">
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_barang }}"
                                    style="width: 140px; height: 140px; object-fit: cover; border-radius: 0.375rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
                            </div>
                            @else
                            <div class="flex h-full items-center justify-center bg-gray-100">
                                <span
                                    class="inline-flex items-center rounded-full bg-gray-200 px-3 py-2 text-sm font-medium text-gray-600">
                                    <i class="fas fa-image mr-2 text-gray-400"></i>
                                    No Image
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Item Info -->
                        <div class="p-4">
                            <h3 class="mb-2 line-clamp-2 text-lg font-semibold text-gray-900">
                                {{ $item->nama_barang }}</h3>

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
                                    <span id="stock-{{ $item->id_barang }}" class="@if ($item->available_stock > 10) text-green-600
                                @elseif($item->available_stock > 5) text-yellow-600
                                @else text-red-600 @endif text-sm font-bold">
                                        {{ $item->available_stock }}
                                    </span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            @if ($item->available_stock > 0)
                            <button id="add-btn-{{ $item->id_barang }}" data-barang-id="{{ $item->id_barang }}"
                                data-barang-nama="{{ addslashes($item->nama_barang) }}"
                                data-satuan="{{ $item->satuan }}" data-current-stock="{{ $item->available_stock }}"
                                onclick="handleAddToCart(this)"
                                class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-900">
                                <i class="fas fa-cart-plus mr-2"></i>
                                Ambil Barang
                            </button>
                            @else
                            <button id="add-btn-{{ $item->id_barang }}" data-barang-id="{{ $item->id_barang }}"
                                data-current-stock="0" disabled
                                class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-md border border-transparent bg-gray-400 px-4 py-2 text-sm font-semibold tracking-widest text-white">
                                Barang Belum Tersedia
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    <div class="mb-4 flex items-center space-x-2">
                        <span class="text-sm text-gray-700">Tampilkan</span>
                        <select name="per_page"
                            onchange="window.location.href = '{{ route('admin.pengambilan.index') }}?per_page=' + this.value + '&search={{ request('search') }}&jenis={{ request('jenis') }}'"
                            class="rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach ([12, 24, 36, 48] as $perPage)
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
                    <i class="fas fa-box-open mb-4 text-6xl text-gray-400"></i>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada barang tersedia</h3>
                    <p class="mt-2 text-gray-500">
                        @if (request('search') || request('jenis'))
                        Tidak ditemukan barang yang sesuai dengan pencarian Anda.
                        @else
                        Saat ini tidak ada barang yang tersedia untuk diambil.
                        @endif
                    </p>
                    @if (request('search') || request('jenis'))
                    <div class="mt-4">
                        <a href="{{ route('admin.pengambilan.index') }}"
                            class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
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
<div id="addToCartModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
    <div class="w-full max-w-lg mx-auto bg-white rounded-lg shadow-lg relative">
        <!-- Modal Header -->
        <div class="bg-gray-600 px-6 py-4 rounded-t-lg">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">
                    Tambah Pengambilan
                </h3>
                <button onclick="closeModal()" class="text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="addToCartForm">
                @csrf
                <input type="hidden" id="id_barang" name="id_barang">

                <!-- Nama Barang Section -->
                <div class="mb-4">
                    <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-box text-gray-500 mr-2"></i>
                        Nama Barang
                    </label>
                    <div class="bg-gray-50 border border-gray-300 rounded-md p-3">
                        <p id="barang_nama" class="font-medium text-gray-900">Loading...</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Stok tersedia: <span id="max_stock" class="font-semibold">0</span> <span
                                id="satuan">pcs</span>
                        </p>
                    </div>
                </div>

                <!-- Quantity Section -->
                <div class="mb-4">
                    <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calculator text-gray-500 mr-2"></i>
                        Jumlah
                    </label>
                    <div class="flex items-center justify-center space-x-3">
                        <button type="button" onclick="decreaseQuantity()"
                            class="w-8 h-8 bg-gray-400 text-white rounded-md flex items-center justify-center">
                            <i class="fas fa-minus text-gray-500"></i>
                        </button>
                        <input type="number" id="quantity" name="quantity" min="1" value="1"
                            class="w-16 h-8 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                        <button type="button" onclick="increaseQuantity()"
                            class="w-8 h-8 bg-gray-400 text-white rounded-md flex items-center justify-center">
                            <i class="fas fa-plus text-gray-500"></i>
                        </button>
                    </div>
                </div>

                <!-- Bidang Section -->
                <div class="mb-4">
                    <label for="bidang" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-building text-gray-500 mr-2"></i>
                        Bidang <span class="text-red-500">*</span>
                    </label>
                    <select id="bidang" name="bidang" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                        <option value="">Pilih Bidang</option>
                        <option value="umum">Umum</option>
                        <option value="perencanaan">Perencanaan</option>
                        <option value="keuangan">Keuangan</option>
                        <option value="operasional">Operasional</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Nama Pengambil Section -->
                <div class="mb-4">
                    <label for="pengambil" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-gray-500 mr-2"></i>
                        Nama Pengambil <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="pengambil" name="pengambil" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-gray-400 focus:border-gray-400"
                        placeholder="Masukkan nama pengambil...">
                </div>

                <!-- Keterangan Section -->
                <div class="mb-4">
                    <label for="keterangan" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-gray-500 mr-2"></i>
                        Keterangan <span class="text-gray-400 text-xs">(opsional)</span>
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-gray-400 focus:border-gray-400 resize-none"
                        placeholder="Keterangan tambahan..."></textarea>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex space-x-3">
            <button onclick="closeModal()" class="flex-1 bg-gray-500 text-white font-semibold py-2 px-4 rounded-md">
                Batal
            </button>
            <button id="addToCartBtn" onclick="addToCart()"
                class="flex-1 bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">
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
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    #addToCartModal {
        display: none;
    }

    #addToCartModal>div {
        margin: auto;
        max-height: calc(100vh - 2rem);
        overflow-y: auto;
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
												fetch('{{ route('admin.cart.count') }}')
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
												console.log('Opening modal with data:', {
																barangId,
																namaBarang,
																satuan,
																stok
												});

												// Check if stock is 0
												if (stok <= 0) {
																Swal.fire({
																				icon: 'warning',
																				title: 'Stok Tidak Tersedia',
																				text: 'Barang belum tersedia. Stok saat ini: 0',
																				confirmButtonColor: '#f59e0b'
																});
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
												const currentValue = parseInt(quantityInput.value) || 0;
												if (currentValue < maxStock) {
																quantityInput.value = currentValue + 1;
												}
								}

								function decreaseQuantity() {
												const quantityInput = document.getElementById('quantity');
												const currentValue = parseInt(quantityInput.value) || 0;
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
																Swal.fire({
																				icon: 'error',
																				title: 'Error!',
																				text: 'CSRF token not found. Please refresh the page.',
																				confirmButtonColor: '#d33'
																});
																return;
												}

												// Validate bidang
												if (!formData.get('bidang')) {
																Swal.fire({
																				icon: 'warning',
																				title: 'Perhatian!',
																				text: 'Silakan pilih bidang terlebih dahulu.',
																				confirmButtonColor: '#f59e0b'
																});
																return;
												}

												// Validate pengambil
												if (!formData.get('pengambil') || formData.get('pengambil').trim() === '') {
																Swal.fire({
																				icon: 'warning',
																				title: 'Perhatian!',
																				text: 'Nama pengambil harus diisi.',
																				confirmButtonColor: '#f59e0b'
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
												addBtn.textContent = 'Menambahkan...';

												fetch('{{ route('admin.cart.add') }}', {
																				method: 'POST',
																				body: formData,
																				headers: {
																								'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
																								'X-Requested-With': 'XMLHttpRequest',
																								'Accept': 'application/json'
																				}
																})
																.then(async response => {
																				console.log('Response status:', response.status);
																				console.log('Response headers:', response.headers);

																				const responseText = await response.text();
																				console.log('Response text:', responseText);

																				if (!response.ok) {
																								throw new Error(`HTTP error! status: ${response.status}, response: ${responseText.substring(0, 200)}...`);
																				}

																				try {
																								return JSON.parse(responseText);
																				} catch (e) {
																								throw new Error(`Invalid JSON response: ${responseText.substring(0, 200)}...`);
																				}
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

																								// Show SweetAlert success message
																								Swal.fire({
																												icon: 'success',
																												title: 'Berhasil!',
																												text: data.message,
																												showConfirmButton: false,
																												timer: 2000,
																												timerProgressBar: true
																								});
																				} else {
																								Swal.fire({
																												icon: 'error',
																												title: 'Gagal!',
																												text: data.message || 'Terjadi kesalahan saat menambahkan ke keranjang.',
																												confirmButtonColor: '#d33'
																								});
																				}
																})
																.catch(error => {
																				console.error('Error details:', error);
																				Swal.fire({
																								icon: 'error',
																								title: 'Terjadi Kesalahan!',
																								text: 'Terjadi kesalahan saat menambahkan ke keranjang: ' + error.message,
																								confirmButtonColor: '#d33'
																				});
																})
																.finally(() => {
																				addBtn.disabled = false;
																				addBtn.textContent = 'Tambah ke Keranjang';
																});
								}

								// Helper function to show messages
								function showMessage(message, type) {
												const alertClass = type === 'success' ? 'bg-blue-100 border-blue-500 text-blue-700' :
																'bg-red-100 border-red-500 text-red-700';
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
																				buttonElement.className =
																								'w-full inline-flex items-center justify-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest cursor-not-allowed';
																				buttonElement.innerHTML = 'Barang Belum Tersedia';
																}
												}
								}

								// Refresh all stocks from server (auto-refresh every 30 seconds)
								function refreshAllStocks() {
												const buttons = document.querySelectorAll('[data-barang-id]');

												buttons.forEach(button => {
																const barangId = button.getAttribute('data-barang-id');

																fetch(`{{ url('/admin/pengambilan/stock') }}/${barangId}`)
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
																																stockElement.className = stockElement.className.replace(
																																				/text-(green|yellow|red)-600/, '');
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
																																				button.className =
																																								'w-full inline-flex items-center justify-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest cursor-not-allowed';
																																				button.innerHTML = 'Barang Belum Tersedia';
																																} else if (button.disabled) {
																																				// Re-enable button if it was disabled but now has stock
																																				button.disabled = false;
																																				button.onclick = function() {
																																								handleAddToCart(this);
																																				};
																																				button.className =
																																								'w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150';
																																				button.innerHTML = `<i class="mr-2 fas fa-cart-plus"></i>Ambil Barang`;
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
