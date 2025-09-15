@extends('layouts.user')

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
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Keranjang Usulan Pengadaan</h2>
                            <p class="mt-1 text-sm text-gray-600">Kelola barang yang diusulkan untuk pengadaan</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('user.usulan.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali ke Katalog
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

                <div id="cart-container">
                    @if($items->count() > 0)
                        <!-- Summary -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
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
                            @foreach($items as $item)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-900 flex items-center">
                                                    <i class="fas fa-box mr-2 text-gray-500"></i>{{ $item->barang->nama_barang }}
                                                </h4>
                                                <p class="text-sm text-gray-600 mt-1 flex items-center">
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
                                                            <i class="fas fa-tag mr-1 text-gray-500"></i>Jenis: {{ ucfirst($item->barang->jenis) }}
                                                    @endswitch
                                                </p>
                                                <p class="text-sm text-gray-500 flex items-center">
                                                    <i class="fas fa-ruler mr-1 text-gray-400"></i>Satuan: {{ $item->barang->satuan }}
                                                </p>
                                                @if($item->keterangan)
                                                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                                                        <i class="fas fa-comment mr-1 text-blue-500"></i>{{ $item->keterangan }}
                                                    </p>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-1 flex items-center">
                                                    <i class="fas fa-clock mr-1"></i>Ditambahkan: {{ $item->created_at->format('d/m/Y H:i') }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-lg font-semibold text-gray-900 flex items-center justify-end">
                                                    <i class="fas fa-shopping-cart mr-2 text-blue-500"></i>{{ $item->jumlah }} {{ $item->barang->satuan }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex items-center space-x-2 ml-4">
                                        <!-- Update Quantity -->
                                        <div class="flex items-center border border-gray-300 rounded">
                                            <button onclick="updateQuantity({{ $item->id }}, -1)"
                                                    class="px-2 py-1 hover:bg-gray-100 transition ease-in-out duration-150 {{ $item->jumlah <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $item->jumlah <= 1 ? 'disabled' : '' }}
                                                    title="Kurangi jumlah">
                                                <i class="fas fa-minus text-xs text-gray-600"></i>
                                            </button>
                                            <span id="quantity-{{ $item->id }}" class="px-3 py-1 text-sm border-l border-r border-gray-300 bg-gray-50 font-medium min-w-[3rem] text-center">{{ $item->jumlah }}</span>
                                            <button onclick="updateQuantity({{ $item->id }}, 1)"
                                                    class="px-2 py-1 hover:bg-gray-100 transition ease-in-out duration-150"
                                                    title="Tambah jumlah">
                                                <i class="fas fa-plus text-xs text-gray-600"></i>
                                            </button>
                                        </div>

                                        <!-- Edit Button -->
                                        <button onclick="showEditModal({{ $item->id }}, '{{ addslashes($item->barang->nama_barang) }}', {{ $item->jumlah }}, '{{ addslashes($item->keterangan ?? '') }}', '{{ $item->barang->satuan }}')"
                                                class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm transition ease-in-out duration-150 inline-flex items-center"
                                                title="Edit usulan">
                                            <i class="fas fa-edit mr-1"></i>
                                            <span class="hidden sm:inline">Edit</span>
                                        </button>

                                        <!-- Remove Button -->
                                        <button onclick="removeItem({{ $item->id }})"
                                                class="bg-red-500 hover:bg-red-700 text-white px-3 py-2 rounded text-sm transition ease-in-out duration-150 inline-flex items-center"
                                                title="Hapus usulan">
                                            <i class="fas fa-trash-alt mr-1"></i>
                                            <span class="hidden sm:inline">Hapus</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-6 mt-6 border-t border-gray-200">
                            <button onclick="clearCart()"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition ease-in-out duration-150 inline-flex items-center"
                                    title="Hapus semua usulan">
                                <i class="fas fa-trash-alt mr-2"></i>Kosongkan Usulan
                            </button>
                            <button onclick="submitUsulan()"
                                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition ease-in-out duration-150 inline-flex items-center"
                                    title="Ajukan semua usulan">
                                <i class="fas fa-paper-plane mr-2"></i>Ajukan Usulan
                            </button>
                        </div>

                    @else
                        <!-- Empty Cart -->
                        <div class="text-center py-12">
                            <div class="mb-4">
                                <i class="fas fa-shopping-cart text-6xl text-gray-400"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-600 mb-2 flex items-center justify-center">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>Belum Ada Usulan
                            </h2>
                            <p class="text-gray-500 mb-6">Anda belum menambahkan barang untuk diusulkan</p>
                            <a href="{{ route('user.usulan.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition ease-in-out duration-150 inline-flex items-center">
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
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Usulan</h3>
            <form id="editForm">
                @csrf
                <input type="hidden" id="edit_id" name="id">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang:</label>
                    <p class="text-sm text-gray-900 font-semibold" id="edit_nama_barang"></p>
                </div>

                <div class="mb-4">
                    <label for="edit_jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah:</label>
                    <div class="flex items-center">
                        <button type="button" onclick="editDecreaseQuantity()" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-l transition ease-in-out duration-150">-</button>
                        <input type="number" id="edit_jumlah" name="jumlah" min="1" value="1" class="border-t border-b border-gray-300 text-center w-20 py-1 focus:ring-blue-500 focus:border-blue-500 transition ease-in-out duration-150">
                        <button type="button" onclick="editIncreaseQuantity()" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-r transition ease-in-out duration-150">+</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="edit_keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (opsional):</label>
                    <textarea id="edit_keterangan" name="keterangan" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 transition ease-in-out duration-150" placeholder="Keterangan tambahan..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition ease-in-out duration-150">
                        Batal
                    </button>
                    <button type="button" onclick="updateItem()" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition ease-in-out duration-150">
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

    fetch(`/user/usulan/cart/update/${id}`, {
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

    fetch(`/user/usulan/cart/update/${id}`, {
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
    if (!confirm('Apakah Anda yakin ingin menghapus usulan ini?')) {
        return;
    }

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'DELETE');

    fetch(`/user/usulan/cart/remove/${id}`, {
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
    if (!confirm('Apakah Anda yakin ingin mengosongkan semua usulan?')) {
        return;
    }

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'DELETE');

    fetch('/user/usulan/cart/clear', {
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
            showMessage(data.message || 'Terjadi kesalahan saat mengosongkan usulan.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Terjadi kesalahan saat mengosongkan usulan.', 'error');
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
        text: 'Apakah Anda yakin ingin mengajukan semua usulan ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ajukan!',
        cancelButtonText: 'Batal',
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
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/user/usulan/cart/submit', {
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
                    const errorMessage = typeof data.message === 'string' ? data.message : 'Terjadi kesalahan saat mengajukan usulan.';
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
