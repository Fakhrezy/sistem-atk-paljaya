<!-- Cart Content -->
<div id="cart-content">
    @if($cartByBidang->isEmpty())
        <div class="py-12 text-center">
            <i class="mb-4 text-6xl text-gray-400 fas fa-clipboard-list"></i>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Keranjang Usulan Kosong</h3>
            <p class="mt-2 text-gray-500">
                Pilih barang dari katalog untuk mengajukan usulan pengadaan.
            </p>
            <div class="mt-4">
                <a href="{{ route('admin.usulan.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="mr-2 fas fa-list"></i>
                    Lihat Katalog Barang
                </a>
            </div>
        </div>
    @else
        <div class="flex flex-col space-y-6">
            @foreach($cartByBidang as $bidang => $cartItems)
                <div class="border border-gray-200 rounded-lg shadow">
                    <!-- Bidang Header -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                        <h3 class="font-semibold text-gray-800">
                            <i class="mr-2 fas fa-tag"></i>
                            Bidang: {{ ucfirst($bidang) }}
                        </h3>
                        <div class="flex items-center space-x-2">
                            <button onclick="submitUsulanBidang('{{ $bidang }}')"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="mr-1 fas fa-paper-plane"></i>
                                Ajukan Usulan
                            </button>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Item</th>
                                    <th class="px-6 py-3">Jumlah Usulan</th>
                                    <th class="px-6 py-3">Keterangan</th>
                                    <th class="px-6 py-3">Pengusul</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $item->barang->nama_barang }}</div>
                                            <div class="text-xs text-gray-500">{{ ucfirst($item->barang->jenis) }}</div>
                                            @if($item->barang->stok < 10)
                                                <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-full text-xs font-medium {{ $item->barang->stok < 5 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    Stok: {{ $item->barang->stok }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="updateQuantity({{ $item->id }}, -1)"
                                                        class="p-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <span id="quantity-{{ $item->id }}" class="px-2 font-medium">{{ $item->quantity }}</span>
                                                <button onclick="updateQuantity({{ $item->id }}, 1)"
                                                        class="p-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <span class="text-sm text-gray-500">{{ $item->barang->satuan }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-500">{{ $item->keterangan ?: 'Tidak ada keterangan' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-gray-900">{{ $item->pengambil ?: 'Belum diisi' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="showEditModal({{ $item->id }}, '{{ addslashes($item->barang->nama_barang) }}', {{ $item->quantity }}, '{{ $item->bidang }}', '{{ addslashes($item->keterangan) }}', '{{ addslashes($item->pengambil) }}', {{ $item->barang->stok }}, '{{ $item->barang->satuan }}')"
                                                        class="p-1 text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="removeItem({{ $item->id }})"
                                                        class="p-1 text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Bidang Summary -->
                    <div class="p-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span>Total Item: {{ $cartItems->count() }}</span>
                            <span>Total Quantity: {{ $cartItems->sum('quantity') }} items</span>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Overall Actions -->
            <div class="flex justify-end mt-6">
                <button onclick="clearCart()"
                        class="inline-flex items-center px-4 py-2 mr-4 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="mr-2 fas fa-trash"></i>
                    Kosongkan Keranjang
                </button>
            </div>
        </div>
    @endif
</div>
