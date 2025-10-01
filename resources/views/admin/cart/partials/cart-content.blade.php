<div id="cart-content">
    @if($cartByBidang->count() > 0)
        <!-- Summary -->
        <div class="p-4 mb-6 border-l-4 border-blue-400 bg-blue-50">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="text-blue-400 fas fa-info-circle"></i>
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
            <div class="mb-8 overflow-hidden border border-gray-200 rounded-lg">
                <!-- Bidang Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="flex items-center text-lg font-semibold text-gray-800">
                        @switch($bidang)
                            @case('umum')
                                <i class="mr-2 text-blue-600 fas fa-users"></i>
                                @break
                            @case('perencanaan')
                                <i class="mr-2 text-green-600 fas fa-chart-line"></i>
                                @break
                            @case('keuangan')
                                <i class="mr-2 text-yellow-600 fas fa-coins"></i>
                                @break
                            @case('operasional')
                                <i class="mr-2 text-purple-600 fas fa-cogs"></i>
                                @break
                            @default
                                <i class="mr-2 text-gray-600 fas fa-building"></i>
                        @endswitch
                        Bidang: {{ ucfirst($bidang) }}
                        <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded flex items-center">
                            <i class="mr-1 fas fa-list"></i>{{ $items->count() }} item
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
                                            <h4 class="flex items-center text-lg font-medium text-gray-900">
                                                <i class="mr-2 text-gray-500 fas fa-box"></i>{{ $item->barang->nama_barang }}
                                            </h4>
                                            <p class="flex items-center mt-1 text-sm text-gray-600">
                                                @switch($item->barang->jenis)
                                                    @case('atk')
                                                        <i class="mr-1 text-gray-500 fas fa-pen"></i>Jenis: ATK
                                                        @break
                                                    @case('cetak')
                                                        <i class="mr-1 text-gray-500 fas fa-print"></i>Jenis: Cetakan
                                                        @break
                                                    @case('tinta')
                                                        <i class="mr-1 text-gray-500 fas fa-tint"></i>Jenis: Tinta
                                                        @break
                                                    @default
                                                        <i class="mr-1 text-gray-500 fas fa-tag"></i>Jenis: {{ ucfirst($item->barang->jenis) }}
                                                @endswitch
                                            </p>
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="mr-1 text-gray-400 fas fa-ruler"></i>Satuan: {{ $item->barang->satuan }}
                                            </p>
                                            @if($item->pengambil)
                                                <p class="flex items-center mt-1 text-sm text-gray-600">
                                                    <i class="mr-1 text-gray-500 fas fa-user"></i>Pengambil: {{ $item->pengambil }}
                                                </p>
                                            @endif
                                            @if($item->keterangan)
                                                <p class="flex items-center mt-1 text-sm text-gray-600">
                                                    <i class="mr-1 text-blue-500 fas fa-comment"></i>{{ $item->keterangan }}
                                                </p>
                                            @endif
                                            <p class="flex items-center mt-1 text-xs text-gray-400">
                                                <i class="mr-1 fas fa-clock"></i>Ditambahkan: {{ $item->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="flex items-center justify-end text-lg font-semibold text-gray-900">
                                                <i class="mr-2 text-blue-500 fas fa-shopping-cart"></i>{{ $item->quantity }} {{ $item->barang->satuan }}
                                            </span>
                                            <p class="flex items-center justify-end text-sm text-gray-500">
                                                <i class="mr-1 text-gray-500 fas fa-warehouse"></i>Stok tersedia: {{ $item->barang->stok }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center ml-4 space-x-2">
                                    <!-- Update Quantity -->
                                    <div class="flex items-center border border-gray-300 rounded">
                                        <button onclick="updateQuantity({{ $item->id }}, -1)"
                                                class="px-2 py-1 hover:bg-gray-100 transition ease-in-out duration-150 {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                                title="Kurangi jumlah">
                                            <i class="text-xs text-gray-600 fas fa-minus"></i>
                                        </button>
                                        <span id="quantity-{{ $item->id }}" class="px-3 py-1 text-sm border-l border-r border-gray-300 bg-gray-50 font-medium min-w-[3rem] text-center">{{ $item->quantity }}</span>
                                        <button onclick="updateQuantity({{ $item->id }}, 1)"
                                                class="px-2 py-1 transition duration-150 ease-in-out hover:bg-gray-100"
                                                title="Tambah jumlah">
                                            <i class="text-xs text-gray-600 fas fa-plus"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Button -->
                                    <button onclick="showEditModal({{ $item->id }}, '{{ addslashes($item->barang->nama_barang) }}', {{ $item->quantity }}, '{{ $item->bidang }}', '{{ addslashes($item->keterangan ?? '') }}', '{{ addslashes($item->pengambil ?? '') }}', {{ $item->barang->stok }}, '{{ $item->barang->satuan }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 text-white transition duration-150 ease-in-out bg-blue-500 rounded hover:bg-blue-700"
                                            title="Edit item dalam keranjang">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Remove Button -->
                                    <button onclick="removeItem({{ $item->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 text-white transition duration-150 ease-in-out bg-gray-400 rounded hover:bg-gray-500"
                                            title="Hapus item dari keranjang">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bidang Action Section -->
                    <div class="pt-4 mt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">{{ $items->count() }} item</span> •
                                <span class="font-medium">{{ $items->sum('quantity') }} unit</span> dalam bidang {{ ucfirst($bidang) }}
                            </div>
                            <button onclick="submitPengambilanBidang('{{ $bidang }}')"
                                    class="inline-flex items-center px-4 py-2 font-semibold text-white transition duration-150 ease-in-out bg-blue-500 rounded-lg hover:bg-blue-600"
                                    title="Ajukan pengambilan untuk bidang {{ ucfirst($bidang) }}">
                                <i class="mr-2 fas fa-paper-plane"></i>Ajukan Pengambilan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Action Buttons -->
        <div class="flex items-center justify-center pt-6 border-t border-gray-200">
            <button onclick="clearCart()" class="inline-flex items-center px-4 py-2 font-bold text-white transition duration-150 ease-in-out bg-red-500 rounded hover:bg-red-700"
                    title="Hapus semua item dari keranjang">
                <i class="mr-2 fas fa-trash-alt"></i>Kosongkan Semua Keranjang
            </button>
        </div>

    @else
        <!-- Empty Cart -->
        <div class="py-12 text-center">
            <div class="mb-4">
                <i class="text-6xl text-gray-400 fas fa-shopping-cart"></i>
            </div>
            <h2 class="flex items-center justify-center mb-2 text-xl font-semibold text-gray-600">
                <i class="mr-2 text-blue-500 fas fa-info-circle"></i>Belum Ada Item untuk Diambil
            </h2>
            <p class="mb-6 text-gray-500">Anda belum menambahkan barang ATK untuk diambil</p>
            <a href="{{ route('admin.pengambilan.index') }}" class="inline-flex items-center px-4 py-2 font-bold text-white transition duration-150 ease-in-out bg-blue-500 rounded hover:bg-blue-700">
                <i class="mr-2 fas fa-plus"></i>Pilih Barang ATK
            </a>
        </div>
    @endif
</div>
