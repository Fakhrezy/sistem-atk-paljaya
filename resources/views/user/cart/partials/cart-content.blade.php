<div id="cart-content">
    @if($cartByBidang->count() > 0)
        <!-- Summary -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Total {{ $cartByBidang->flatten()->count() }} item siap diambil, tersebar di {{ $cartByBidang->count() }} bidang
                    </p>
                </div>
            </div>
        </div>

        <!-- Cart Items Grouped by Bidang -->
        @foreach($cartByBidang as $bidang => $items)
            <div class="mb-8 border border-gray-200 rounded-lg overflow-hidden">
                <!-- Bidang Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        @switch($bidang)
                            @case('umum')
                                <i class="fas fa-users mr-2 text-blue-600"></i>
                                @break
                            @case('perencanaan')
                                <i class="fas fa-chart-line mr-2 text-green-600"></i>
                                @break
                            @case('keuangan')
                                <i class="fas fa-coins mr-2 text-yellow-600"></i>
                                @break
                            @case('operasional')
                                <i class="fas fa-cogs mr-2 text-purple-600"></i>
                                @break
                            @default
                                <i class="fas fa-building mr-2 text-gray-600"></i>
                        @endswitch
                        Bidang: {{ ucfirst($bidang) }}
                        <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded flex items-center">
                            <i class="fas fa-list mr-1"></i>{{ $items->count() }} item
                        </span>
                    </h3>
                </div>

                <!-- Items in this Bidang -->
                <div class="p-6">
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
                                                        <i class="fas fa-pen mr-1 text-gray-500"></i>Jenis: ATK
                                                        @break
                                                    @case('cetak')
                                                        <i class="fas fa-print mr-1 text-gray-500"></i>Jenis: Cetakan
                                                        @break
                                                    @case('tinta')
                                                        <i class="fas fa-tint mr-1 text-gray-500"></i>Jenis: Tinta
                                                        @break
                                                    @default
                                                        <i class="fas fa-tag mr-1 text-gray-500"></i>Jenis: {{ ucfirst($item->barang->jenis) }}
                                                @endswitch
                                            </p>
                                            <p class="text-sm text-gray-500 flex items-center">
                                                <i class="fas fa-ruler mr-1 text-gray-400"></i>Satuan: {{ $item->barang->satuan }}
                                            </p>
                                            @if($item->pengambil)
                                                <p class="text-sm text-gray-600 mt-1 flex items-center">
                                                    <i class="fas fa-user mr-1 text-gray-500"></i>Pengambil: {{ $item->pengambil }}
                                                </p>
                                            @endif
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
                                                <i class="fas fa-shopping-cart mr-2 text-blue-500"></i>{{ $item->quantity }} {{ $item->barang->satuan }}
                                            </span>
                                            <p class="text-sm text-gray-500 flex items-center justify-end">
                                                <i class="fas fa-warehouse mr-1 text-gray-500"></i>Stok tersedia: {{ $item->barang->stok }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-2 ml-4">
                                    <!-- Update Quantity -->
                                    <div class="flex items-center border border-gray-300 rounded">
                                        <button onclick="updateQuantity({{ $item->id }}, -1)"
                                                class="px-2 py-1 hover:bg-gray-100 transition ease-in-out duration-150 {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                                title="Kurangi jumlah">
                                            <i class="fas fa-minus text-xs text-gray-600"></i>
                                        </button>
                                        <span id="quantity-{{ $item->id }}" class="px-3 py-1 text-sm border-l border-r border-gray-300 bg-gray-50 font-medium min-w-[3rem] text-center">{{ $item->quantity }}</span>
                                        <button onclick="updateQuantity({{ $item->id }}, 1)"
                                                class="px-2 py-1 hover:bg-gray-100 transition ease-in-out duration-150"
                                                title="Tambah jumlah">
                                            <i class="fas fa-plus text-xs text-gray-600"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Button -->
                                    <button onclick="showEditModal({{ $item->id }}, '{{ addslashes($item->barang->nama_barang) }}', {{ $item->quantity }}, '{{ $item->bidang }}', '{{ addslashes($item->keterangan ?? '') }}', '{{ addslashes($item->pengambil ?? '') }}', {{ $item->barang->stok }}, '{{ $item->barang->satuan }}')"
                                            class="w-8 h-8 bg-blue-500 hover:bg-blue-700 text-white rounded transition ease-in-out duration-150 inline-flex items-center justify-center"
                                            title="Edit item dalam keranjang">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Remove Button -->
                                    <button onclick="removeItem({{ $item->id }})"
                                            class="w-8 h-8 bg-gray-400 hover:bg-gray-500 text-white rounded transition ease-in-out duration-150 inline-flex items-center justify-center"
                                            title="Hapus item dari keranjang">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bidang Action Section -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">{{ $items->count() }} item</span> •
                                <span class="font-medium">{{ $items->sum('quantity') }} unit</span> dalam bidang {{ ucfirst($bidang) }}
                            </div>
                            <button onclick="submitPengambilanBidang('{{ $bidang }}')"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition ease-in-out duration-150 inline-flex items-center"
                                    title="Ajukan pengambilan untuk bidang {{ ucfirst($bidang) }}">
                                <i class="fas fa-paper-plane mr-2"></i>Ajukan Pengambilan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Action Buttons -->
        <div class="flex justify-center items-center pt-6 border-t border-gray-200">
            <button onclick="clearCart()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition ease-in-out duration-150 inline-flex items-center"
                    title="Hapus semua item dari keranjang">
                <i class="fas fa-trash-alt mr-2"></i>Kosongkan Semua Keranjang
            </button>
        </div>

    @else
        <!-- Empty Cart -->
        <div class="text-center py-12">
            <div class="mb-4">
                <i class="fas fa-shopping-cart text-6xl text-gray-400"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-600 mb-2 flex items-center justify-center">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>Belum Ada Item untuk Diambil
            </h2>
            <p class="text-gray-500 mb-6">Anda belum menambahkan barang ATK untuk diambil</p>
            <a href="{{ route('user.pengambilan.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition ease-in-out duration-150 inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>Pilih Barang ATK
            </a>
        </div>
    @endif
</div>
