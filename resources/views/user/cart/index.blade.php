@extends('layouts.user')

@section('title', 'Keranjang Barang')

@section('header')
    Keranjang Barang
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-full">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Keranjang Pengambilan</h2>
                            <p class="mt-1 text-sm text-gray-600">Kelola barang ATK yang akan diambil</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('user.pengambilan.index') }}"
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
                    <!-- Cart content loaded server-side -->
                    @include('user.cart.partials.cart-content', ['cartByBidang' => $cartByBidang])
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editItemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Item Keranjang</h3>
            <div class="mt-2 px-7 py-3">
                <form id="editItemForm">
                    @csrf
                    <input type="hidden" id="edit_cart_id" name="cart_id">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang:</label>
                        <p class="text-sm text-gray-900 font-semibold" id="edit_barang_nama"></p>
                    </div>

                    <div class="mb-4">
                        <label for="edit_quantity" class="block text-sm font-medium text-gray-700 mb-2">Jumlah:</label>
                        <div class="flex items-center">
                            <button type="button" onclick="editDecreaseQuantity()" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-l transition ease-in-out duration-150">-</button>
                            <input type="number" id="edit_quantity" name="quantity" min="1" value="1" class="border-t border-b border-gray-300 text-center w-20 py-1 focus:ring-blue-500 focus:border-blue-500 transition ease-in-out duration-150">
                            <button type="button" onclick="editIncreaseQuantity()" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-r transition ease-in-out duration-150">+</button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Maksimal: <span id="edit_max_stock"></span> <span id="edit_satuan"></span></p>
                    </div>

                    <div class="mb-4">
                        <label for="edit_bidang" class="block text-sm font-medium text-gray-700 mb-2">Bidang:</label>
                        <select id="edit_bidang" name="bidang" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 transition ease-in-out duration-150">
                            <option value="">Pilih Bidang</option>
                            <option value="umum">Umum</option>
                            <option value="perencanaan">Perencanaan</option>
                            <option value="keuangan">Keuangan</option>
                            <option value="operasional">Operasional</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="edit_keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (opsional):</label>
                        <textarea id="edit_keterangan" name="keterangan" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 transition ease-in-out duration-150" placeholder="Keterangan tambahan..."></textarea>
                    </div>
                </form>
            </div>
            <div class="items-center px-4 py-3">
                <button id="updateItemBtn" onclick="updateCartItem()" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition ease-in-out duration-150">
                    Update Item
                </button>
                <button onclick="closeEditModal()" class="mt-3 px-4 py-2 bg-gray-300 text-black text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition ease-in-out duration-150">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div id="checkoutModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Ajukan Pengambilan ATK</h3>
            <div class="mt-2 px-7 py-3">
                <form id="checkoutForm">
                    @csrf

                    <div class="mb-4">
                        <label for="nama_pengambil" class="block text-sm font-medium text-gray-700 mb-2">Nama Pengambil:</label>
                        <input type="text" id="nama_pengambil" name="nama_pengambil" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 transition ease-in-out duration-150"
                               placeholder="Masukkan nama pengambil...">
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Semua item dalam keranjang akan diproses untuk pengambilan ATK.</p>
                    </div>
                </form>
            </div>
            <div class="items-center px-4 py-3">
                <button id="checkoutBtn" onclick="processCheckout()" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition ease-in-out duration-150">
                    Ajukan Pengambilan
                </button>
                <button onclick="closeCheckoutModal()" class="mt-3 px-4 py-2 bg-gray-300 text-black text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition ease-in-out duration-150">
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
        <div class="flex justify-between items-center">
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
    if (!confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
        return;
    }

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
            showMessage(data.message, 'success');
        } else {
            alert(data.message || 'Terjadi kesalahan saat mengosongkan keranjang.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengosongkan keranjang.');
    });
}

// Edit item modal functions
function showEditModal(cartId, namaBarang, quantity, bidang, keterangan, maxStock, satuan) {
    document.getElementById('edit_cart_id').value = cartId;
    document.getElementById('edit_barang_nama').textContent = namaBarang;
    document.getElementById('edit_quantity').value = quantity;
    document.getElementById('edit_quantity').max = maxStock;
    document.getElementById('edit_bidang').value = bidang;
    document.getElementById('edit_keterangan').value = keterangan || '';
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

// Pengambilan modal functions
function showCheckoutModal() {
    document.getElementById('nama_pengambil').value = '';
    document.getElementById('checkoutModal').classList.remove('hidden');
}

function closeCheckoutModal() {
    document.getElementById('checkoutModal').classList.add('hidden');
}

function processCheckout() {
    const form = document.getElementById('checkoutForm');
    const formData = new FormData(form);
    const checkoutBtn = document.getElementById('checkoutBtn');

    if (!formData.get('nama_pengambil')) {
        alert('Silakan masukkan nama pengambil.');
        return;
    }

    checkoutBtn.disabled = true;
    checkoutBtn.textContent = 'Mengajukan...';

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
            closeCheckoutModal();
            loadCartContent();
            showMessage(data.message, 'success');
        } else {
            alert(data.message || 'Terjadi kesalahan saat mengajukan pengambilan.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengajukan pengambilan.');
    })
    .finally(() => {
        checkoutBtn.disabled = false;
        checkoutBtn.textContent = 'Ajukan Pengambilan';
    });
}

// Utility function to show messages
function showMessage(message, type) {
    const alertClass = type === 'success' ? 'bg-blue-100 border-blue-500 text-blue-700' : 'bg-red-100 border-red-500 text-red-700';
    const alertDiv = document.createElement('div');
    alertDiv.className = `mb-4 ${alertClass} border-l-4 p-4 rounded relative`;
    alertDiv.innerHTML = `<span class="block sm:inline">${message}</span>`;

    const contentDiv = document.querySelector('.p-6.text-gray-900');
    contentDiv.insertBefore(alertDiv, contentDiv.children[1]);

    // Remove alert after 3 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Direct submit pengambilan function (called from cart-content partial)
function submitPengambilan() {
    showCheckoutModal();
}

// Close modals when clicking outside
window.onclick = function(event) {
    const editModal = document.getElementById('editItemModal');
    const checkoutModal = document.getElementById('checkoutModal');

    if (event.target == editModal) {
        closeEditModal();
    }
    if (event.target == checkoutModal) {
        closeCheckoutModal();
    }
}
</script>

@endsection
