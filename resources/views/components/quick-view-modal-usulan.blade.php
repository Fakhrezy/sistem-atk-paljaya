<!-- Add to Cart Modal -->
<div id="quickViewModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 items-center justify-center p-4 hidden">
    <div class="relative w-full max-w-lg mx-auto my-8 bg-white rounded-lg shadow-xl overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-blue-600 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">
                    <i class="fas fa-cart-plus mr-2"></i>
                    Buat Usulan Pengadaan
                </h3>
                <button onclick="closeQuickView()" class="text-blue-100 hover:text-white">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="modalForm" onsubmit="submitForm(event)">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="barang_id" id="modalBarangId">

                <!-- Nama Barang -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-box mr-1"></i>
                        Nama Barang
                    </label>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p id="modalTitle" class="text-base font-medium text-gray-900 mb-2">Loading...</p>
                        <div class="text-sm text-gray-500">
                            <span>Jenis: <span id="modalJenis" class="font-semibold">-</span></span>
                        </div>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-calculator mr-1"></i>
                        Jumlah yang Diusulkan
                    </label>
                    <div class="flex items-center justify-center space-x-4">
                        <button type="button" onclick="decreaseQuantity()"
                                class="w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full text-gray-700 flex items-center justify-center">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" id="modalJumlah" name="jumlah" min="1" value="1"
                               class="w-20 h-10 text-center text-lg font-medium border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" onclick="increaseQuantity()"
                                class="w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full text-gray-700 flex items-center justify-center">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeQuickView()"
                            class="px-6 py-3 text-base font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                            class="px-6 py-3 text-base font-medium text-white bg-blue-600 border-2 border-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-cart-plus mr-2"></i>
                        Tambah ke Keranjang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal styles */
#quickViewModal.show {
    display: flex !important;
}

#quickViewModal {
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

<!-- Script untuk Modal -->
<script>
function showQuickView(barang) {
    // Update modal content
    document.getElementById('modalTitle').textContent = barang.nama_barang;
    document.getElementById('modalJenis').textContent = ucfirst(barang.jenis);
    document.getElementById('modalBarangId').value = barang.id_barang;
    document.getElementById('modalJumlah').value = 1;

    // Setup form action
    document.getElementById('modalForm').action = "{{ route('user.katalog-usulan.add-to-cart') }}";

    // Show modal with animation
    const modal = document.getElementById('quickViewModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}

function closeQuickView() {
    const modal = document.getElementById('quickViewModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('modalJumlah');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function increaseQuantity() {
    const quantityInput = document.getElementById('modalJumlah');
    const currentValue = parseInt(quantityInput.value);
    quantityInput.value = currentValue + 1;
}

function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('quickViewModal');
    if (event.target == modal) {
        closeQuickView();
    }
}

// Close modal with ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('quickViewModal');
        if (modal.classList.contains('show')) {
            closeQuickView();
        }
    }
});

// Handle form submission
function submitForm(e) {
    e.preventDefault();

    const form = document.getElementById('modalForm');
    const formData = new FormData(form);
    const submitButton = document.getElementById('submitBtn');
    const originalText = submitButton.innerHTML;

    // Disable submit button
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menambahkan...';

    fetch('{{ route("user.katalog-usulan.add-to-cart") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count if provided
            if (data.totalItems !== undefined) {
                const cartCountElement = document.querySelector('.text-xs');
                if (cartCountElement) {
                    cartCountElement.textContent = data.totalItems;
                }
            }

            // Show success message
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#3085d6'
            });

            // Close modal
            closeQuickView();
        } else {
            // Show error message
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Terjadi kesalahan saat menambahkan barang ke keranjang.',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show error message
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat menambahkan barang ke keranjang.',
            icon: 'error',
            confirmButtonColor: '#3085d6'
        });
    })
    .finally(() => {
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});
</script>
</script>
