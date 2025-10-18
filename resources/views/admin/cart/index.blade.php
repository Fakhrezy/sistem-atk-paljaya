@extends('layouts.admin')

@section('title', 'Keranjang Barang')

@section('header')
SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
@endsection



@section('content')
<div class="h-full">
    <div class="max-w-full">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Keranjang Pengambilan</h2>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.pengambilan.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out bg-gray-600 border border-transparent rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>

                @if(session('success'))
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

                @if(session('error'))
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
                    <!-- Cart content loaded server-side -->
                    @include('admin.cart.partials.cart-content', ['cartByBidang' => $cartByBidang])
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editItemModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-lg mx-auto bg-white rounded-lg shadow-lg">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-gray-600 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">
                        Edit Item Keranjang
                    </h3>
                    <button onclick="closeEditModal()" class="text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="editItemForm">
                    @csrf
                    <input type="hidden" id="edit_cart_id" name="cart_id">

                    <!-- Nama Barang Section -->
                    <div class="mb-4">
                        <label class="flex items-center mb-2 text-sm font-medium text-gray-700">
                            <i class="mr-2 text-gray-500 fas fa-box"></i>
                            Nama Barang
                        </label>
                        <div class="p-3 border border-gray-300 rounded-md bg-gray-50">
                            <p class="font-medium text-gray-900" id="edit_barang_nama"></p>
                        </div>
                    </div>

                    <!-- Quantity Section -->
                    <div class="mb-4">
                        <label for="edit_quantity" class="flex items-center mb-2 text-sm font-medium text-gray-700">
                            <i class="mr-2 text-gray-500 fas fa-calculator"></i>
                            Jumlah
                        </label>
                        <div class="flex items-center justify-center space-x-3">
                            <button type="button" onclick="editDecreaseQuantity()"
                                class="flex items-center justify-center w-8 h-8 text-white bg-gray-400 rounded-md">
                                <i class="text-gray-500 fas fa-minus"></i>
                            </button>
                            <input type="number" id="edit_quantity" name="quantity" min="1" value="1"
                                class="w-16 h-8 text-center border border-gray-300 rounded-md focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                            <button type="button" onclick="editIncreaseQuantity()"
                                class="flex items-center justify-center w-8 h-8 text-white bg-gray-400 rounded-md">
                                <i class="text-gray-500 fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Bidang Section -->
                    <div class="mb-4">
                        <label for="edit_bidang" class="flex items-center mb-2 text-sm font-medium text-gray-700">
                            <i class="mr-2 text-gray-500 fas fa-building"></i>
                            Bidang
                        </label>
                        <select id="edit_bidang" name="bidang" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                            <option value="">Pilih Bidang</option>
                            @foreach(\App\Constants\BidangConstants::getBidangList() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nama Pengambil Section -->
                    <div class="mb-4">
                        <label for="edit_pengambil" class="flex items-center mb-2 text-sm font-medium text-gray-700">
                            <i class="mr-2 text-gray-500 fas fa-user"></i>
                            Nama Pengambil
                        </label>
                        <input type="text" id="edit_pengambil" name="pengambil" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-gray-400 focus:border-gray-400"
                            placeholder="Masukkan nama pengambil...">
                    </div>

                    <!-- Keterangan Section -->
                    <div class="mb-4">
                        <label for="edit_keterangan" class="flex items-center mb-2 text-sm font-medium text-gray-700">
                            <i class="mr-2 text-gray-500 fas fa-sticky-note"></i>
                            Keterangan <span class="text-xs text-gray-400">(opsional)</span>
                        </label>
                        <textarea id="edit_keterangan" name="keterangan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md resize-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400"
                            placeholder="Keterangan tambahan..."></textarea>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex px-6 py-4 space-x-3 rounded-b-lg bg-gray-50">
                <button onclick="closeEditModal()"
                    class="flex-1 px-4 py-2 font-semibold text-white bg-gray-500 rounded-md">
                    Batal
                </button>
                <button id="updateItemBtn" onclick="updateCartItem()"
                    class="flex-1 px-4 py-2 font-semibold text-white bg-blue-600 rounded-md">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Bidang constants for JavaScript
const bidangNames = @json(\App\Constants\BidangConstants::getBidangList());

    // Load cart content when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadCartContent();
});

// Show message notification
function showMessage(message, type = 'info') {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.notification-message');
    existingMessages.forEach(msg => msg.remove());

    const alertClass = type === 'success' ? 'bg-blue-100 border-blue-500 text-blue-700' :
                     type === 'error' ? 'bg-red-100 border-red-500 text-red-700' :
                     'bg-blue-100 border-blue-500 text-blue-700';

    const messageDiv = document.createElement('div');
    messageDiv.className = `notification-message mb-4 ${alertClass} border-l-4 p-4 rounded relative`;
    messageDiv.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="block sm:inline">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-current hover:opacity-75">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    // Insert at the top of cart container
    const cartContainer = document.getElementById('cart-container');
    cartContainer.insertBefore(messageDiv, cartContainer.firstChild);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 5000);
}

// Load cart content
function loadCartContent() {
    fetch('{{ route("admin.cart.index") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const cartContent = doc.querySelector('#cart-content');
        if (cartContent) {
            document.getElementById('cart-container').innerHTML = cartContent.outerHTML;
        } else {
            console.warn('Cart content not found in response');
            // Reload the page if partial content fails
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error loading cart:', error);
        showMessage('Gagal memuat keranjang. Silakan refresh halaman.', 'error');
    });
}

// Update cart item quantity
function updateQuantity(cartId, change) {
    const currentQuantitySpan = document.querySelector(`#quantity-${cartId}`);
    const currentQuantity = parseInt(currentQuantitySpan.textContent);
    const newQuantity = currentQuantity + change;

    if (newQuantity < 1) return;

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('quantity', newQuantity);

    fetch(`{{ url('admin/cart/update') }}/${cartId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            loadCartContent();
        } else {
            showMessage(data.message || 'Terjadi kesalahan saat mengupdate quantity.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Terjadi kesalahan saat mengupdate quantity.', 'error');
    });
}

// Remove item from cart
function removeItem(cartId) {
    Swal.fire({
        title: 'Hapus Item?',
        text: 'Yakin ingin menghapus item ini dari keranjang?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6b7280',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<i class="fas fa-trash"></i> Hapus!',
        cancelButtonText: '<i class="fas fa-times"></i> Batal'
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'DELETE');

    fetch(`{{ url('admin/cart/remove') }}/${cartId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            loadCartContent();
        } else {
            showMessage(data.message || 'Terjadi kesalahan saat menghapus item.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Terjadi kesalahan saat menghapus item.', 'error');
    });
    });
}

// Clear cart
function clearCart() {
    Swal.fire({
        title: 'Kosongkan Keranjang?',
        text: 'Yakin ingin mengosongkan semua item di keranjang?',
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
                title: 'Mengosongkan Keranjang...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'DELETE');

            fetch('{{ route("admin.cart.clear") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadCartContent();
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan saat mengosongkan keranjang.',
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
                    text: 'Terjadi kesalahan saat mengosongkan keranjang.',
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
                });
            });
        }
    });
}

// Edit item modal functions
function showEditModal(cartId, namaBarang, quantity, bidang, keterangan, pengambil, maxStock, satuan) {
    document.getElementById('edit_cart_id').value = cartId;
    document.getElementById('edit_barang_nama').textContent = namaBarang;
    document.getElementById('edit_quantity').value = quantity;
    document.getElementById('edit_quantity').max = maxStock;
    document.getElementById('edit_bidang').value = bidang;
    document.getElementById('edit_keterangan').value = keterangan || '';
    document.getElementById('edit_pengambil').value = pengambil || '';

    document.getElementById('editItemModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editItemModal').classList.add('hidden');
}

function editIncreaseQuantity() {
    const quantityInput = document.getElementById('edit_quantity');
    const maxStock = parseInt(quantityInput.max);
    const currentValue = parseInt(quantityInput.value) || 0;

    if (currentValue < maxStock) {
        quantityInput.value = currentValue + 1;
    }
}

function editDecreaseQuantity() {
    const quantityInput = document.getElementById('edit_quantity');
    const currentValue = parseInt(quantityInput.value) || 0;

    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function updateCartItem() {
    const form = document.getElementById('editItemForm');
    const formData = new FormData(form);
    const updateBtn = document.getElementById('updateItemBtn');
    const cartId = document.getElementById('edit_cart_id').value;

    // Validate required fields
    const bidang = formData.get('bidang');
    const pengambil = formData.get('pengambil');

    if (!bidang) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: 'Silakan pilih bidang terlebih dahulu.',
            confirmButtonColor: '#f59e0b'
        });
        return;
    }

    if (!pengambil || pengambil.trim() === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: 'Nama pengambil harus diisi.',
            confirmButtonColor: '#f59e0b'
        });
        return;
    }

    updateBtn.disabled = true;
    updateBtn.textContent = 'Mengupdate...';

    fetch(`{{ url('admin/cart/update') }}/${cartId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditModal();
            loadCartContent();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan saat mengupdate item.'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan saat mengupdate item.'
        });
    })
    .finally(() => {
        updateBtn.disabled = false;
        updateBtn.textContent = 'Simpan';
    });
}

// Submit pengambilan untuk bidang tertentu
function submitPengambilanBidang(bidang) {
    // Tampilkan SweetAlert konfirmasi
    Swal.fire({
        title: 'Konfirmasi Pengambilan',
        text: `Yakin ingin lakukan pengambilan untuk semua item di bidang ${bidangNames[bidang] || bidang}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: '<i class="mr-2 fas fa-check"></i>Simpan',
        cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            processCheckoutDirect(bidang);
        }
    });
}

// Process checkout langsung tanpa modal input nama pengambil
function processCheckoutDirect(bidang) {
    const formData = new FormData();
    formData.append('bidang', bidang);

    // Tampilkan loading
    Swal.fire({
        title: 'Memproses Pengajuan...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('{{ route("admin.cart.checkout") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                loadCartContent(); // Refresh cart content
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan saat melakukan pengambilan.',
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
            text: 'Terjadi kesalahan saat melakukan pengambilan.',
            icon: 'error',
            confirmButtonColor: '#dc2626',
            confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
        });
    });
}

// Close modals when clicking outside
window.onclick = function(event) {
    const editModal = document.getElementById('editItemModal');
    if (event.target == editModal) {
        closeEditModal();
    }
}
</script>

@endsection