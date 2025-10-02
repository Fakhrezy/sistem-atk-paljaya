@extends('layouts.admin')

@section('title', 'Keranjang Usulan')

@section('header')
SISTEM MONITORING BARANG HABIS PAKAI
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
                            <h2 class="text-2xl font-semibold text-gray-800">Keranjang Pengadaan</h2>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.usulan.index') }}"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:bg-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Katalog
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

                <div id="cart-container">
                    @if ($items->count() > 0)
                    <!-- Summary -->
                    <div class="mb-6 border-l-4 border-blue-400 bg-blue-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Total {{ $items->count() }} item dalam usulan pengadaan
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="space-y-4">
                        @foreach ($items as $item)
                        <div
                            class="flex items-center justify-between rounded-lg border border-gray-200 p-4 hover:bg-gray-50">
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="flex items-center text-lg font-medium text-gray-900">
                                            <i class="fas fa-box mr-2 text-gray-500"></i>{{ $item->barang->nama_barang
                                            }}
                                        </h4>
                                        <p class="mt-1 flex items-center text-sm text-gray-600">
                                            @switch($item->barang->jenis)
                                            @case('atk')
                                            <i class="fas fa-pen mr-1 text-blue-500"></i>Jenis: ATK
                                            @break

                                            @case('cetak')
                                            <i class="fas fa-print mr-1 text-green-500"></i>Jenis: Cetakan
                                            @break

                                            @case('tinta')
                                            <i class="fas fa-tint mr-1 text-purple-500"></i>Jenis: Tinta
                                            @break

                                            @default
                                            <i class="fas fa-tag mr-1 text-gray-500"></i>Jenis:
                                            {{ ucfirst($item->barang->jenis) }}
                                            @endswitch
                                        </p>
                                        <p class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-ruler mr-1 text-gray-400"></i>Satuan:
                                            {{ $item->barang->satuan }}
                                        </p>
                                        @if ($item->keterangan)
                                        <p class="mt-1 flex items-center text-sm text-gray-600">
                                            <i class="fas fa-comment mr-1 text-blue-500"></i>{{ $item->keterangan }}
                                        </p>
                                        @endif
                                        <p class="mt-1 flex items-center text-xs text-gray-400">
                                            <i class="fas fa-clock mr-1"></i>Ditambahkan:
                                            {{ $item->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="flex items-center justify-end text-lg font-semibold text-gray-900">
                                            <i class="fas fa-shopping-cart mr-2 text-blue-500"></i>{{ $item->jumlah }}
                                            {{ $item->barang->satuan }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="ml-4 flex items-center space-x-2">
                                <!-- Update Quantity -->
                                <div class="flex items-center rounded border border-gray-300">
                                    <button onclick="updateQuantity({{ $item->id }}, -1)"
                                        class="{{ $item->jumlah <= 1 ? 'opacity-50 cursor-not-allowed' : '' }} px-2 py-1 transition duration-150 ease-in-out hover:bg-gray-100"
                                        {{ $item->jumlah <= 1 ? 'disabled' : '' }} title="Kurangi jumlah">
                                            <i class="fas fa-minus text-xs text-gray-600"></i>
                                    </button>
                                    <span id="quantity-{{ $item->id }}"
                                        class="min-w-[3rem] border-l border-r border-gray-300 bg-gray-50 px-3 py-1 text-center text-sm font-medium">{{
                                        $item->jumlah }}</span>
                                    <button onclick="updateQuantity({{ $item->id }}, 1)"
                                        class="px-2 py-1 transition duration-150 ease-in-out hover:bg-gray-100"
                                        title="Tambah jumlah">
                                        <i class="fas fa-plus text-xs text-gray-600"></i>
                                    </button>
                                </div>

                                <!-- Edit Button -->
                                <button
                                    onclick="showEditModal({{ $item->id }}, '{{ addslashes($item->barang->nama_barang) }}', {{ $item->jumlah }}, '{{ addslashes($item->keterangan ?? '') }}', '{{ $item->barang->satuan }}')"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded bg-blue-500 text-white transition duration-150 ease-in-out hover:bg-blue-700"
                                    title="Edit usulan">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Remove Button -->
                                <button onclick="removeItem({{ $item->id }})"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded bg-gray-400 text-white transition duration-150 ease-in-out hover:bg-gray-500"
                                    title="Hapus usulan">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex items-center justify-between border-t border-gray-200 pt-6">
                        <button onclick="clearCart()"
                            class="inline-flex items-center rounded bg-red-500 px-4 py-2 font-bold text-white transition duration-150 ease-in-out hover:bg-red-700"
                            title="Hapus semua usulan">
                            <i class="fas fa-trash-alt mr-2"></i>Kosongkan Keranjang
                        </button>
                        <button onclick="submitUsulan()"
                            class="inline-flex items-center rounded-lg bg-blue-500 px-4 py-2 font-semibold text-white transition duration-150 ease-in-out hover:bg-blue-600"
                            title="Ajukan semua usulan">
                            <i class="fas fa-paper-plane mr-2"></i>Tambah Pengadaan
                        </button>
                    </div>
                    @else
                    <!-- Empty Cart -->
                    <div class="py-12 text-center">
                        <div class="mb-4">
                            <i class="fas fa-shopping-cart text-6xl text-gray-400"></i>
                        </div>
                        <h2 class="mb-2 flex items-center justify-center text-xl font-semibold text-gray-600">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>Belum Ada Usulan
                        </h2>
                        <p class="mb-6 text-gray-500">Anda belum menambahkan barang untuk diusulkan</p>
                        <a href="{{ route('admin.usulan.index') }}"
                            class="inline-flex items-center rounded bg-blue-500 px-4 py-2 font-bold text-white transition duration-150 ease-in-out hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Buat Usulan
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 hidden z-50 overflow-y-auto bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-lg mx-auto bg-white rounded-lg shadow-lg">
            <!-- Modal Header -->
            <div class="bg-gray-600 px-6 py-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">
                        Edit Usulan
                    </h3>
                    <button onclick="closeEditModal()" class="text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="editForm">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">

                    <!-- Nama Barang Section -->
                    <div class="mb-4">
                        <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-box text-gray-500 mr-2"></i>
                            Nama Barang
                        </label>
                        <div class="bg-gray-50 border border-gray-300 rounded-md p-3">
                            <p class="font-medium text-gray-900" id="edit_nama_barang"></p>
                        </div>
                    </div>

                    <!-- Quantity Section -->
                    <div class="mb-4">
                        <label for="edit_jumlah" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calculator text-gray-500 mr-2"></i>
                            Jumlah
                        </label>
                        <div class="flex items-center justify-center space-x-3">
                            <button type="button" onclick="editDecreaseQuantity()"
                                class="w-8 h-8 bg-gray-400 text-white rounded-md flex items-center justify-center">
                                <i class="fas fa-minus text-gray-500"></i>
                            </button>
                            <input type="number" id="edit_jumlah" name="jumlah" min="1" value="1"
                                class="w-16 h-8 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                            <button type="button" onclick="editIncreaseQuantity()"
                                class="w-8 h-8 bg-gray-400 text-white rounded-md flex items-center justify-center">
                                <i class="fas fa-plus text-gray-500"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Keterangan Section -->
                    <div class="mb-4">
                        <label for="edit_keterangan" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note text-gray-500 mr-2"></i>
                            Keterangan <span class="text-gray-400 text-xs">(opsional)</span>
                        </label>
                        <textarea id="edit_keterangan" name="keterangan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-gray-400 focus:border-gray-400 resize-none"
                            placeholder="Keterangan tambahan..."></textarea>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex space-x-3">
                <button type="button" onclick="closeEditModal()"
                    class="flex-1 bg-gray-500 text-white font-semibold py-2 px-4 rounded-md">
                    Batal
                </button>
                <button type="button" onclick="updateItem()"
                    class="flex-1 bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function updateQuantity(id, change) {
												const currentQuantitySpan = document.querySelector(`#quantity-${id}`);
												const currentQuantity = parseInt(currentQuantitySpan.textContent);
												const newQuantity = currentQuantity + change;

												if (newQuantity < 1) return;

												const formData = new FormData();
												formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
												formData.append('jumlah', newQuantity);

												fetch(`/admin/usulan/cart/update/${id}`, {
																				method: 'POST',
																				body: formData,
																				headers: {
																								'X-Requested-With': 'XMLHttpRequest'
																				}
																})
																.then(response => response.json())
																.then(data => {
																				if (data.success) {
																								currentQuantitySpan.textContent = newQuantity;
																								showMessage(data.message, 'success');
																				} else {
																								showMessage(data.message || 'Terjadi kesalahan saat mengupdate jumlah.', 'error');
																				}
																})
																.catch(error => {
																				console.error('Error:', error);
																				showMessage('Terjadi kesalahan saat mengupdate jumlah.', 'error');
																});
								}

								function showEditModal(id, namaBarang, jumlah, keterangan) {
												document.getElementById('edit_id').value = id;
												document.getElementById('edit_nama_barang').textContent = namaBarang;
												document.getElementById('edit_jumlah').value = jumlah;
												document.getElementById('edit_keterangan').value = keterangan || '';
												document.getElementById('editModal').classList.remove('hidden');
								}

								function closeEditModal() {
												document.getElementById('editModal').classList.add('hidden');
								}

								function editDecreaseQuantity() {
												const input = document.getElementById('edit_jumlah');
												const currentValue = parseInt(input.value) || 0;
												if (currentValue > 1) {
																input.value = currentValue - 1;
												}
								}

								function editIncreaseQuantity() {
												const input = document.getElementById('edit_jumlah');
												const currentValue = parseInt(input.value) || 0;
												input.value = currentValue + 1;
								}

								function updateItem() {
												const id = document.getElementById('edit_id').value;
												const jumlah = parseInt(document.getElementById('edit_jumlah').value);

												if (jumlah < 1) {
																Swal.fire({
																				icon: 'warning',
																				title: 'Perhatian!',
																				text: 'Jumlah harus lebih dari 0.'
																});
																return;
												}

												const formData = new FormData(document.getElementById('editForm'));

												fetch(`/admin/usulan/cart/update/${id}`, {
																				method: 'POST',
																				body: formData,
																				headers: {
																								'X-Requested-With': 'XMLHttpRequest'
																				}
																})
																.then(response => response.json())
																.then(data => {
																				if (data.success) {
																								closeEditModal();
																								Swal.fire({
																												icon: 'success',
																												title: 'Berhasil!',
																												text: data.message,
																												timer: 2000,
																												showConfirmButton: false
																								}).then(() => {
																												window.location.reload();
																								});
																				} else {
																								Swal.fire({
																												icon: 'error',
																												title: 'Gagal!',
																												text: data.message || 'Terjadi kesalahan saat mengupdate usulan.'
																								});
																				}
																})
																.catch(error => {
																				console.error('Error:', error);
																				Swal.fire({
																								icon: 'error',
																								title: 'Error!',
																								text: 'Terjadi kesalahan saat mengupdate usulan.'
																				});
																});
								}

								function removeItem(id) {
												Swal.fire({
																title: 'Hapus Pengadaan?',
																text: 'Yakin ingin menghapus pengadaan ini?',
																icon: 'warning',
																showCancelButton: true,
																confirmButtonColor: '#6b7280',
																cancelButtonColor: '#3085d6',
																confirmButtonText: '<i class="fas fa-trash"></i> Hapus!',
																cancelButtonText: '<i class="fas fa-times"></i> Batal'
												}).then((result) => {
																if (result.isConfirmed) {
																				Swal.fire({
																								title: 'Menghapus...',
																								text: 'Mohon tunggu sebentar.',
																								icon: 'info',
																								allowOutsideClick: false,
																								showConfirmButton: false,
																								didOpen: () => {
																												Swal.showLoading();
																								}
																				});

																				const formData = new FormData();
																				formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
																				formData.append('_method', 'DELETE');

																				fetch(`/admin/usulan/cart/remove/${id}`, {
																								method: 'POST',
																								body: formData,
																								headers: {
																												'X-Requested-With': 'XMLHttpRequest'
																								}
																				})
																				.then(response => response.json())
																				.then(data => {
																								if (data.success) {
																												Swal.fire({
																																icon: 'success',
																																title: 'Berhasil!',
																																text: 'Usulan berhasil dihapus.',
																																showConfirmButton: false,
																																timer: 1500
																												}).then(() => {
																																window.location.reload();
																												});
																								} else {
																												Swal.fire({
																																icon: 'error',
																																title: 'Gagal!',
																																text: data.message || 'Terjadi kesalahan saat menghapus usulan.',
																																confirmButtonColor: '#d33'
																												});
																								}
																				})
																				.catch(error => {
																								console.error('Error:', error);
																								Swal.fire({
																												icon: 'error',
																												title: 'Error!',
																												text: 'Terjadi kesalahan saat menghapus usulan.',
																												confirmButtonColor: '#d33'
																								});
																				});
																}
												});
								}

								function clearCart() {
												Swal.fire({
																title: 'Kosongkan Keranjang?',
																text: 'Yakin ingin mengosongkan semua keranjang ini?',
																icon: 'warning',
																showCancelButton: true,
																confirmButtonColor: '#dc2626',
																cancelButtonColor: '#6b7280',
																confirmButtonText: '<i class="mr-2 fas fa-trash"></i>Kosongkan!',
																cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
																reverseButtons: true
												}).then((result) => {
																if (result.isConfirmed) {
																				// Show loading state
																				Swal.fire({
																								title: 'Mengosongkan Usulan...',
																								text: 'Mohon tunggu sebentar',
																								allowOutsideClick: false,
																								showConfirmButton: false,
																								didOpen: () => {
																												Swal.showLoading();
																								}
																				});

																				const formData = new FormData();
																				formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
																								'content'));
																				formData.append('_method', 'DELETE');

																				fetch('/admin/usulan/cart/clear', {
																												method: 'POST',
																												body: formData,
																												headers: {
																																'X-Requested-With': 'XMLHttpRequest'
																												}
																								})
																								.then(response => response.json())
																								.then(data => {
																												if (data.success) {
																																Swal.fire({
																																				title: 'Berhasil!',
																																				text: data.message,
																																				icon: 'success',
																																				confirmButtonColor: '#16a34a',
																																				confirmButtonText: '<i class="mr-2 fas fa-check"></i>OK'
																																}).then(() => {
																																				window.location.reload();
																																});
																												} else {
																																Swal.fire({
																																				title: 'Gagal!',
																																				text: data.message || 'Terjadi kesalahan saat mengosongkan usulan.',
																																				icon: 'error',
																																				confirmButtonColor: '#dc2626',
																																				confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
																																});
																												}
																								})
																								.catch(error => {
																												console.error('Error:', error);
																												Swal.fire({
																																title: 'Error!',
																																text: 'Terjadi kesalahan saat mengosongkan usulan.',
																																icon: 'error',
																																confirmButtonColor: '#dc2626',
																																confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
																												});
																								});
																}
												});
								}

								function showMessage(message, type = 'info') {
												const alertClass = type === 'success' ? 'bg-blue-100 border-blue-500 text-blue-700' :
																type === 'error' ? 'bg-red-100 border-red-500 text-red-700' :
																'bg-blue-100 border-blue-500 text-blue-700';

												const messageDiv = document.createElement('div');
												messageDiv.className = `mb-4 ${alertClass} border-l-4 p-4 rounded relative`;
												messageDiv.innerHTML = `<span class="block sm:inline">${message}</span>`;

												const container = document.querySelector('.p-6.text-gray-900');
												container.insertBefore(messageDiv, container.firstChild);

												setTimeout(() => {
																messageDiv.remove();
												}, 5000);
								}

								function submitUsulan() {
												Swal.fire({
																title: 'Ajukan Usulan',
																text: 'Yakin ingin mengajukan semua usulan ini?',
																icon: 'question',
																showCancelButton: true,
																confirmButtonColor: '#3085d6',
																cancelButtonColor: '#6B7280',
																confirmButtonText: '<i class="mr-2 fas fa-check"></i>Ajukan!',
																cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
																reverseButtons: true
												}).then((result) => {
																if (result.isConfirmed) {
																				// Tampilkan loading
																				Swal.fire({
																								title: 'Memproses...',
																								text: 'Mohon tunggu sebentar',
																								icon: 'info',
																								allowOutsideClick: false,
																								allowEscapeKey: false,
																								showConfirmButton: false,
																								didOpen: () => {
																												Swal.showLoading();
																								}
																				});

																				const formData = new FormData();
																				formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
																								'content'));

																				fetch('/admin/usulan/cart/submit', {
																												method: 'POST',
																												body: formData,
																												headers: {
																																'X-Requested-With': 'XMLHttpRequest'
																												}
																								})
																								.then(async response => {
																												const data = await response.json();
																												if (!response.ok) {
																																throw new Error(data.message || 'Terjadi kesalahan pada server');
																												}
																												return data;
																								})
																								.then(data => {
																												if (data.success) {
																																Swal.fire({
																																				title: 'Berhasil!',
																																				text: data.message,
																																				icon: 'success',
																																				timer: 2000,
																																				showConfirmButton: false
																																}).then(() => {
																																				window.location.href = '/admin/usulan';
																																});
																												} else {
																																const errorMessage = typeof data.message === 'string' ? data.message :
																																				'Terjadi kesalahan saat mengajukan usulan.';
																																Swal.fire({
																																				title: 'Error!',
																																				text: errorMessage,
																																				icon: 'error',
																																				confirmButtonText: 'OK'
																																});
																												}
																								})
																								.catch(error => {
																												console.error('Error:', error);
																												const errorMessage = error.message || 'Terjadi kesalahan saat mengajukan usulan.';
																												Swal.fire({
																																title: 'Error!',
																																text: errorMessage,
																																icon: 'error',
																																confirmButtonText: 'OK'
																												});
																								});
																}
												});
								}

								// Close modal when clicking outside
								window.onclick = function(event) {
												const modal = document.getElementById('editModal');
												if (event.target == modal) {
																closeEditModal();
												}
								}
</script>

@endsection
