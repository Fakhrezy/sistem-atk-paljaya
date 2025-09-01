@extends('layouts.user')

@section('title', 'Keranjang Pengambilan')

@section('header')
    Keranjang Pengambilan
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-full">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 w-full">
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Keranjang Pengambilan</h2>
                            <p class="mt-1 text-sm text-gray-600">Kelola barang yang akan diambil</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('user.pengambilan.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Barang
                            </a>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded relative">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded relative">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if($cartItems->count() > 0)
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4" id="cart-item-{{ $item->id }}">
                                <div class="flex items-center space-x-4">
                                    <!-- Item Image -->
                                    <div class="flex-shrink-0 w-16 h-16">
                                        @if($item->barang->foto)
                                            <img src="{{ asset('storage/'.$item->barang->foto) }}"
                                                 alt="{{ $item->barang->nama_barang }}"
                                                 class="w-16 h-16 object-cover rounded-md">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Item Details -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $item->barang->nama_barang }}</h3>
                                        <div class="mt-1 text-sm text-gray-600">
                                            <p>Jenis: {{ ucfirst($item->barang->jenis) }} | Satuan: {{ $item->barang->satuan }}</p>
                                            <p>Stok Tersedia: <span class="font-medium">{{ $item->barang->stok }}</span></p>
                                            @if($item->bidang)
                                                <p>Bidang: <span class="font-medium">{{ $item->bidang }}</span></p>
                                            @endif
                                            @if($item->keterangan)
                                                <p>Keterangan: <span class="font-medium">{{ $item->keterangan }}</span></p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-3">
                                        <button type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>

                                        <input type="number"
                                               value="{{ $item->quantity }}"
                                               min="1"
                                               max="{{ $item->barang->stok }}"
                                               class="w-16 text-center border border-gray-300 rounded-md"
                                               onchange="updateQuantity({{ $item->id }}, this.value)"
                                               id="quantity-{{ $item->id }}">

                                        <button type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center"
                                                {{ $item->quantity >= $item->barang->stok ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="flex-shrink-0">
                                        <button type="button"
                                                onclick="removeFromCart({{ $item->id }})"
                                                class="text-red-600 hover:text-red-900 p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Cart Summary and Actions -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Summary -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Keranjang</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Item:</span>
                                        <span class="font-medium">{{ $cartItems->count() }} jenis barang</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Kuantitas:</span>
                                        <span class="font-medium">{{ $cartItems->sum('quantity') }} unit</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col space-y-4">
                                <button type="button"
                                        onclick="clearCart()"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Kosongkan Keranjang
                                </button>

                                <form action="{{ route('user.cart.checkout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Proses Pengambilan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty Cart -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8.5m-8.5 0L12 21"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Keranjang kosong</h3>
                        <p class="mt-2 text-gray-500">Belum ada barang yang ditambahkan ke keranjang</p>
                        <div class="mt-6">
                            <a href="{{ route('user.pengambilan.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Mulai Tambah Barang
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function updateQuantity(cartId, newQuantity) {
    if (newQuantity < 1) return;

    fetch(`/user/cart/${cartId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`quantity-${cartId}`).value = newQuantity;
            location.reload(); // Reload to update summary
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate kuantitas');
    });
}

function removeFromCart(cartId) {
    if (!confirm('Apakah Anda yakin ingin menghapus barang ini dari keranjang?')) {
        return;
    }

    fetch(`/user/cart/${cartId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`cart-item-${cartId}`).remove();
            updateCartCount();

            // Reload if no items left
            if (document.querySelectorAll('[id^="cart-item-"]').length === 0) {
                location.reload();
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus barang');
    });
}

function clearCart() {
    if (!confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')) {
        return;
    }

    fetch('/user/cart', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengosongkan keranjang');
    });
}

function updateCartCount() {
    fetch('/user/cart/count')
    .then(response => response.json())
    .then(data => {
        const cartBadge = document.getElementById('cart-count');
        if (cartBadge) {
            cartBadge.textContent = data.count;
            cartBadge.style.display = data.count > 0 ? 'inline' : 'none';
        }
    })
    .catch(error => console.error('Error updating cart count:', error));
}
</script>
@endsection
