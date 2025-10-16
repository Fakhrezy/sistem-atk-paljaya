<div id="cart-content">
    @if($cartItems->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border">
        <!-- Cart Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">
                    Item dalam Keranjang ({{ $cartItems->count() }} item)
                </h3>
                <button onclick="clearCart()" class="text-red-600 hover:text-red-800 text-sm font-medium">
                    Kosongkan Keranjang
                </button>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="divide-y divide-gray-200">
            @foreach($cartItems as $item)
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <!-- Item Image -->
                    <div class="flex-shrink-0 w-16 h-16">
                        @if($item->barang->foto)
                        <img src="{{ asset('storage/'.$item->barang->foto) }}" alt="{{ $item->barang->nama_barang }}"
                            class="w-16 h-16 object-cover rounded-lg">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                    </div>

                    <!-- Item Details -->
                    <div class="flex-1 min-w-0">
                        <h4 class="text-lg font-medium text-gray-900 truncate">
                            {{ $item->barang->nama_barang }}
                        </h4>
                        <div class="mt-1 text-sm text-gray-500 space-y-1">
                            <p><span class="font-medium">Jenis:</span> {{ ucfirst($item->barang->jenis) }}</p>
                            <p><span class="font-medium">Bidang:</span> {{
                                \App\Constants\BidangConstants::getBidangName($item->bidang) }}</p>
                            @if($item->keterangan)
                            <p><span class="font-medium">Keterangan:</span> {{ $item->keterangan }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Quantity Controls -->
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button onclick="updateQuantity({{ $item->id }}, -1)"
                                class="p-2 hover:bg-gray-100 rounded-l-lg" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4" />
                                    </svg>
                            </button>
                            <span id="quantity-{{ $item->id }}" class="px-4 py-2 text-sm font-medium">
                                {{ $item->quantity }}
                            </span>
                            <button onclick="updateQuantity({{ $item->id }}, 1)"
                                class="p-2 hover:bg-gray-100 rounded-r-lg" {{ $item->quantity >= $item->barang->stok ?
                                'disabled' : '' }}>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </button>
                        </div>
                        <span class="text-sm text-gray-500">{{ $item->barang->satuan }}</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        <button
                            onclick="showEditModal({{ $item->id }}, '{{ $item->barang->nama_barang }}', {{ $item->quantity }}, '{{ $item->bidang }}', '{{ $item->keterangan }}', {{ $item->barang->stok }}, '{{ $item->barang->satuan }}')"
                            class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="removeItem({{ $item->id }})"
                            class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Cart Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Total: {{ $cartItems->count() }} item, {{ $cartItems->sum('quantity') }} unit
                </div>
                <button onclick="showCheckoutModal()"
                    class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Ajukan Pengambilan
                </button>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-12 bg-white rounded-lg shadow-sm border">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Keranjang Kosong</h3>
        <p class="mt-2 text-gray-500">Belum ada barang yang ditambahkan ke keranjang.</p>
        <div class="mt-6">
            <a href="{{ route('user.pengambilan.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Lihat Katalog ATK
            </a>
        </div>
    </div>
    @endif
</div>