@extends('layouts.admin')

@section('title', 'Keranjang Usulan')

@section('header')
Keranjang Usulan
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
                            <h2 class="text-2xl font-semibold text-gray-800">Keranjang Usulan Pengadaan</h2>
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
                <div class="relative mb-4 rounded border-l-4 border-blue-500 bg-blue-100 p-4 text-blue-700">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                @if (session('error'))
                <div class="relative mb-4 rounded border-l-4 border-red-500 bg-red-100 p-4 text-red-700">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
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
                            <i class="fas fa-trash-alt mr-2"></i>Kosongkan Usulan
                        </button>
                        <button onclick="submitUsulan()"
                            class="inline-flex items-center rounded-lg bg-blue-500 px-4 py-2 font-semibold text-white transition duration-150 ease-in-out hover:bg-blue-600"
                            title="Ajukan semua usulan">
                            <i class="fas fa-paper-plane mr-2"></i>Ajukan Usulan
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
<div id="editModal" class="fixed inset-0 hidden h-full w-full overflow-y-auto bg-gray-600 bg-opacity-50">
    <div class="relative top-20 mx-auto w-96 rounded-md border bg-white p-5 shadow-lg">
        <div class="mt-3">
            <h3 class="mb-4 text-lg font-medium text-gray-900">Edit Usulan</h3>
            <form id="editForm">
                @csrf
                <input type="hidden" id="edit_id" name="id">

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Nama Barang:</label>
                    <p class="text-sm font-semibold text-gray-900" id="edit_nama_barang"></p>
                </div>

                <div class="mb-4">
                    <label for="edit_jumlah" class="mb-2 block text-sm font-medium text-gray-700">Jumlah:</label>
                    <div class="flex items-center">
                        <button type="button" onclick="editDecreaseQuantity()"
                            class="rounded-l bg-gray-200 px-3 py-1 transition duration-150 ease-in-out hover:bg-gray-300">-</button>
                        <input type="number" id="edit_jumlah" name="jumlah" min="1" value="1"
                            class="w-20 border-b border-t border-gray-300 py-1 text-center transition duration-150 ease-in-out focus:border-blue-500 focus:ring-blue-500">
                        <button type="button" onclick="editIncreaseQuantity()"
                            class="rounded-r bg-gray-200 px-3 py-1 transition duration-150 ease-in-out hover:bg-gray-300">+</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="edit_keterangan" class="mb-2 block text-sm font-medium text-gray-700">Keterangan
                        (opsional):</label>
                    <textarea id="edit_keterangan" name="keterangan" rows="3"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 transition duration-150 ease-in-out focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Keterangan tambahan..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()"
                        class="rounded-md bg-gray-300 px-4 py-2 text-base font-medium text-gray-700 transition duration-150 ease-in-out hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                    <button type="button" onclick="updateItem()"
                        class="rounded-md bg-blue-500 px-4 py-2 text-base font-medium text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Simpan
                    </button>
                </div>
            </form>
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
												const currentValue = parseInt(input.value);
												if (currentValue > 1) {
																input.value = currentValue - 1;
												}
								}

								function editIncreaseQuantity() {
												const input = document.getElementById('edit_jumlah');
												input.value = parseInt(input.value) + 1;
								}

								function updateItem() {
												const id = document.getElementById('edit_id').value;
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
																								window.location.reload();
																				} else {
																								showMessage(data.message || 'Terjadi kesalahan saat mengupdate usulan.', 'error');
																				}
																})
																.catch(error => {
																				console.error('Error:', error);
																				showMessage('Terjadi kesalahan saat mengupdate usulan.', 'error');
																});
								}

								function removeItem(id) {
												if (!confirm('Yakin ingin menghapus usulan ini?')) {
																return;
												}

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
																								window.location.reload();
																				} else {
																								showMessage(data.message || 'Terjadi kesalahan saat menghapus usulan.', 'error');
																				}
																})
																.catch(error => {
																				console.error('Error:', error);
																				showMessage('Terjadi kesalahan saat menghapus usulan.', 'error');
																});
								}

								function clearCart() {
												Swal.fire({
																title: 'Kosongkan Usulan?',
																text: 'Yakin ingin mengosongkan semua usulan ini?',
																icon: 'warning',
																showCancelButton: true,
																confirmButtonColor: '#dc2626',
																cancelButtonColor: '#6b7280',
																confirmButtonText: '<i class="mr-2 fas fa-trash"></i>Ya, Kosongkan!',
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
																																				window.location.href = '/user/usulan';
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
