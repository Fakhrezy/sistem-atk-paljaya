@extends('layouts.user')

@section('title', 'Keranjang Barang')

@section('header')
    Keranjang Barang
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
                            <a href="{{ route('user.pengambilan.index') }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out bg-gray-600 border border-transparent rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali ke Katalog
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

                <div id="cart-container">
                    <!-- Cart content loaded server-side -->
                    @include('user.cart.partials.cart-content', ['cartByBidang' => $cartByBidang])
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editItemModal" class="fixed inset-0 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50">
    <div class="relative p-5 mx-auto bg-white border rounded-md shadow-lg top-20 w-96">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Item Keranjang</h3>
            <div class="py-3 mt-2 px-7">
                <form id="editItemForm">
                    @csrf
                    <input type="hidden" id="edit_cart_id" name="cart_id">

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Nama Barang:</label>
                        <p class="text-sm font-semibold text-gray-900" id="edit_barang_nama"></p>
                    </div>

                    <div class="mb-4">
                        <label for="edit_quantity" class="block mb-2 text-sm font-medium text-gray-700">Jumlah:</label>
                        <div class="flex items-center">
                            <button type="button" onclick="editDecreaseQuantity()" class="px-3 py-1 transition duration-150 ease-in-out bg-gray-200 rounded-l hover:bg-gray-300">-</button>
                            <input type="number" id="edit_quantity" name="quantity" min="1" value="1" class="w-20 py-1 text-center transition duration-150 ease-in-out border-t border-b border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" onclick="editIncreaseQuantity()" class="px-3 py-1 transition duration-150 ease-in-out bg-gray-200 rounded-r hover:bg-gray-300">+</button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Maksimal: <span id="edit_max_stock"></span> <span id="edit_satuan"></span></p>
                    </div>

                    <div class="mb-4">
                        <label for="edit_bidang" class="block mb-2 text-sm font-medium text-gray-700">Bidang:</label>
                        <select id="edit_bidang" name="bidang" required class="w-full px-3 py-2 transition duration-150 ease-in-out border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Bidang</option>
                            <option value="umum">Umum</option>
                            <option value="perencanaan">Perencanaan</option>
                            <option value="keuangan">Keuangan</option>
                            <option value="operasional">Operasional</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="edit_pengambil" class="block mb-2 text-sm font-medium text-gray-700">Nama Pengambil:</label>
                        <input type="text" id="edit_pengambil" name="pengambil" required class="w-full px-3 py-2 transition duration-150 ease-in-out border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nama pengambil...">
                    </div>

                    <div class="mb-4">
                        <label for="edit_keterangan" class="block mb-2 text-sm font-medium text-gray-700">Keterangan (opsional):</label>
                        <textarea id="edit_keterangan" name="keterangan" rows="3" class="w-full px-3 py-2 transition duration-150 ease-in-out border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Keterangan tambahan..."></textarea>
                    </div>
                </form>
            </div>
            <div class="items-center px-4 py-3">
                <button id="updateItemBtn" onclick="updateCartItem()" class="w-full px-4 py-2 text-base font-medium text-white transition duration-150 ease-in-out bg-blue-500 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Update Item
                </button>
                <button onclick="closeEditModal()" class="w-full px-4 py-2 mt-3 text-base font-medium text-black transition duration-150 ease-in-out bg-gray-300 rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
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
    fetch('{{ route("user.cart.index") }}', {
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

    fetch(`{{ url('user/cart/update') }}/${cartId}`, {
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
    if (!confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
        return;
    }

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'DELETE');

    fetch(`{{ url('user/cart/remove') }}/${cartId}`, {
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
}

// Clear cart
function clearCart() {
    Swal.fire({
        title: 'Kosongkan Keranjang?',
        text: 'Apakah Anda yakin ingin mengosongkan semua item di keranjang?',
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

            fetch('{{ route("user.cart.clear") }}', {
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
                        confirmButtonColor: '#16a34a',
                        confirmButtonText: '<i class="mr-2 fas fa-check"></i>OK'
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
    document.getElementById('edit_max_stock').textContent = maxStock;
    document.getElementById('edit_satuan').textContent = satuan;
    document.getElementById('editItemModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editItemModal').classList.add('hidden');
}

function editIncreaseQuantity() {
    const quantityInput = document.getElementById('edit_quantity');
    const maxStock = parseInt(document.getElementById('edit_max_stock').textContent);
    const currentValue = parseInt(quantityInput.value);
    if (currentValue < maxStock) {
        quantityInput.value = currentValue + 1;
    }
}

function editDecreaseQuantity() {
    const quantityInput = document.getElementById('edit_quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function updateCartItem() {
    const form = document.getElementById('editItemForm');
    const formData = new FormData(form);
    const updateBtn = document.getElementById('updateItemBtn');
    const cartId = document.getElementById('edit_cart_id').value;

    // Validate bidang
    if (!formData.get('bidang')) {
        alert('Silakan pilih bidang terlebih dahulu.');
        return;
    }

    updateBtn.disabled = true;
    updateBtn.textContent = 'Mengupdate...';

    fetch(`{{ url('user/cart/update') }}/${cartId}`, {
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
            showMessage(data.message, 'success');
        } else {
            alert(data.message || 'Terjadi kesalahan saat mengupdate item.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate item.');
    })
    .finally(() => {
        updateBtn.disabled = false;
        updateBtn.textContent = 'Update Item';
    });
}

// Submit pengambilan untuk bidang tertentu
function submitPengambilanBidang(bidang) {
    // Tampilkan SweetAlert konfirmasi
    Swal.fire({
        title: 'Konfirmasi Pengambilan',
        text: `Apakah Anda yakin ingin mengajukan pengambilan untuk semua item di bidang ${bidang.charAt(0).toUpperCase() + bidang.slice(1)}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: '<i class="mr-2 fas fa-check"></i>Ajukan!',
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

    fetch('{{ route("user.cart.checkout") }}', {
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
                confirmButtonColor: '#16a34a',
                confirmButtonText: '<i class="mr-2 fas fa-check"></i>OK'
            }).then(() => {
                loadCartContent(); // Refresh cart content
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan saat mengajukan pengambilan.',
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
            text: 'Terjadi kesalahan saat mengajukan pengambilan.',
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
