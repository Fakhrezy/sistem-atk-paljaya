@extends('layouts.admin')

@section('title', 'Usulan Pengadaan')

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
                            <p class="mt-1 text-sm text-gray-600">Pilih barang untuk mengajukan usulan pengadaan</p>
                        </div>
                        <!-- Cart Link -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('user.usulan.cart.index') }}"
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
                <div class="relative mb-4 rounded border-l-4 border-blue-500 bg-blue-100 p-4 text-blue-700">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                @if (session('error'))
                <div class="relative mb-4 rounded border-l-4 border-red-500 bg-red-100 p-4 text-red-700">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif

                <!-- Search and Filter -->
                <div class="mb-6">
                    <form action="{{ route('user.usulan.index') }}" method="GET"
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
                        <a href="{{ route('user.usulan.index', ['per_page' => request('per_page', 12)]) }}"
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
                                    <span class="@if ($item->available_stock > 10) text-green-600
                                                @elseif($item->available_stock > 5) text-yellow-600
                                                @else text-red-600 @endif text-sm font-bold">
                                        {{ $item->available_stock }}
                                    </span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button
                                onclick="showUsulanModal('{{ $item->id_barang }}', '{{ addslashes($item->nama_barang) }}', '{{ $item->satuan }}')"
                                class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-900">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Ajukan Usulan
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    <div class="mb-4 flex items-center space-x-2">
                        <span class="text-sm text-gray-700">Tampilkan</span>
                        <select name="per_page"
                            onchange="window.location.href = '{{ route('user.usulan.index') }}?per_page=' + this.value + '&search={{ request('search') }}&jenis={{ request('jenis') }}'"
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
                        Saat ini tidak ada barang yang tersedia untuk diusulkan.
                        @endif
                    </p>
                    @if (request('search') || request('jenis'))
                    <div class="mt-4">
                        <a href="{{ route('user.usulan.index') }}"
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

<!-- Usulan Modal -->
<div id="usulanModal"
    class="fixed inset-0 z-50 hidden h-full w-full items-center justify-center overflow-y-auto bg-black bg-opacity-50 p-4">
    <div class="relative mx-auto my-8 w-full max-w-lg overflow-hidden rounded-lg bg-white shadow-xl">
        <!-- Modal Header -->
        <div class="border-b border-gray-200 bg-blue-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Form Usulan Pengadaan
                </h3>
                <button onclick="closeModal()" class="text-blue-100 hover:text-white">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="usulanForm">
                @csrf
                <input type="hidden" id="id_barang" name="barang_id">

                <!-- Nama Barang -->
                <div class="mb-5">
                    <label class="mb-3 block text-sm font-medium text-gray-700">
                        <i class="fas fa-box mr-1"></i>
                        Nama Barang
                    </label>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <p id="barang_nama" class="mb-2 text-base font-medium text-gray-900">Loading...</p>
                        <p class="text-sm text-gray-500">
                            Satuan: <span id="satuan">pcs</span>
                        </p>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="mb-5">
                    <label class="mb-3 block text-sm font-medium text-gray-700">
                        <i class="fas fa-calculator mr-1"></i>
                        Jumlah yang Diusulkan <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center justify-center space-x-4">
                        <button type="button" onclick="decreaseQuantity()"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" id="jumlah" name="jumlah" min="1" value="1"
                            class="h-10 w-20 rounded-lg border-2 border-gray-300 text-center text-lg font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        <button type="button" onclick="increaseQuantity()"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mb-5">
                    <label for="keterangan" class="mb-3 block text-sm font-medium text-gray-700">
                        <i class="fas fa-sticky-note mr-1"></i>
                        Keterangan Usulan <span class="text-gray-500">(opsional)</span>
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full resize-none rounded-lg border-2 border-gray-300 px-3 py-3 text-base focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                        placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end space-x-3 border-t border-gray-200 bg-gray-50 px-6 py-4">
            <button onclick="closeModal()"
                class="rounded-lg border-2 border-gray-300 bg-white px-6 py-3 text-base font-medium text-gray-700 hover:bg-gray-50">
                <i class="fas fa-times mr-2"></i>
                Batal
            </button>
            <button id="submitUsulanBtn" onclick="submitUsulan()"
                class="rounded-lg border-2 border-blue-600 bg-blue-600 px-6 py-3 text-base font-medium text-white hover:bg-blue-700">
                <i class="fas fa-paper-plane mr-2"></i>
                Ajukan Usulan
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
    #usulanModal.show {
        display: flex !important;
    }

    #usulanModal {
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

<script>
    function showUsulanModal(barangId, namaBarang, satuan) {
												document.getElementById('id_barang').value = barangId;
												document.getElementById('barang_nama').textContent = namaBarang;
												document.getElementById('satuan').textContent = satuan;
												document.getElementById('jumlah').value = 1;
												document.getElementById('keterangan').value = '';

												const modal = document.getElementById('usulanModal');
												modal.classList.remove('hidden');
												document.body.style.overflow = 'hidden'; // Prevent background scroll
												setTimeout(() => {
																modal.classList.add('show');
												}, 10);
								}

								function closeModal() {
												const modal = document.getElementById('usulanModal');
												modal.classList.remove('show');
												document.body.style.overflow = 'auto'; // Restore background scroll
												setTimeout(() => {
																modal.classList.add('hidden');
												}, 300);
								}

								function increaseQuantity() {
												const quantityInput = document.getElementById('jumlah');
												const currentValue = parseInt(quantityInput.value);
												quantityInput.value = currentValue + 1;
								}

								function decreaseQuantity() {
												const quantityInput = document.getElementById('jumlah');
												const currentValue = parseInt(quantityInput.value);
												if (currentValue > 1) {
																quantityInput.value = currentValue - 1;
												}
								}

								function submitUsulan() {
												const form = document.getElementById('usulanForm');
												const formData = new FormData(form);
												const submitBtn = document.getElementById('submitUsulanBtn');
												const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

												console.log('CSRF Token:', csrfToken);

												// Log form data
												console.log('Form data:', Object.fromEntries(formData));

												submitBtn.disabled = true;
												submitBtn.innerHTML = `
        <i class="mr-2 fas fa-spinner fa-spin"></i>
        Memproses...
    `;

												fetch('{{ route('user.usulan.cart.add') }}', {
																				method: 'POST',
																				body: formData,
																				headers: {
																								'X-CSRF-TOKEN': csrfToken,
																								'Accept': 'application/json',
																								'X-Requested-With': 'XMLHttpRequest'
																				},
																				credentials: 'same-origin'
																})
																.then(response => {
																				if (!response.ok) {
																								return response.json().then(err => {
																												throw new Error(err.message || 'Terjadi kesalahan pada server');
																								});
																				}
																				return response.json();
																})
																.then(data => {
																				if (data.success) {
																								closeModal();

																								// Show success message
																								const successDiv = document.createElement('div');
																								successDiv.className =
																												'mb-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded relative';
																								successDiv.innerHTML = `<span class="block sm:inline">${data.message}</span>`;

																								const contentDiv = document.querySelector('.p-6.text-gray-900');
																								if (contentDiv) {
																												contentDiv.insertBefore(successDiv, contentDiv.firstChild);

																												// Remove success message after 3 seconds
																												setTimeout(() => {
																																successDiv.remove();
																												}, 3000);
																								}

																								// Reset form
																								form.reset();
																				} else {
																								throw new Error(data.message || 'Terjadi kesalahan saat mengajukan usulan');
																				}
																})
																.catch(error => {
																				console.error('Error:', error);
																				alert(error.message);
																})
																.finally(() => {
																				submitBtn.disabled = false;
																				submitBtn.innerHTML = `
            <i class="mr-2 fas fa-paper-plane"></i>
            Ajukan Usulan
        `;
																});
								}

								// Close modal when clicking outside
								// Load cart count when page loads
								document.addEventListener('DOMContentLoaded', function() {
												updateCartCount();
								});

								// Update cart count
								function updateCartCount() {
												fetch('{{ route('user.usulan.cart.count') }}')
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

								window.onclick = function(event) {
												const modal = document.getElementById('usulanModal');
												if (event.target == modal) {
																closeModal();
												}
								}

								// Close modal with ESC key
								document.addEventListener('keydown', function(event) {
												if (event.key === 'Escape') {
																const modal = document.getElementById('usulanModal');
																if (modal.classList.contains('show')) {
																				closeModal();
																}
												}
								});
</script>
@endsection
