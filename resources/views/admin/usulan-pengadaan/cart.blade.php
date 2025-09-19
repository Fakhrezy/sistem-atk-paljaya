@extends('layouts.admin')

@section('title', 'Keranjang Usulan Pengadaan')

@section('header', 'SISTEM INFORMASI MONITORING BARANG HABIS PAKAI')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Keranjang Usulan Pengadaan</h1>
        <a href="{{ route('admin.usulan.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Keranjang Kosong</h3>
            <p class="text-gray-500 mt-2">
                Anda belum menambahkan barang apapun ke keranjang.
            </p>
            <div class="mt-4">
                <a href="{{ route('admin.usulan.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Pilih Barang
                </a>
            </div>
        </div>
    @else
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item->barang->nama_barang }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->barang->jenis }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <button onclick="updateQuantity({{ $item->id }}, -1)" class="bg-gray-200 text-gray-700 px-2 py-1 rounded">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span id="quantity-{{ $item->id }}" class="text-sm font-medium text-gray-900">
                                        {{ $item->jumlah }}
                                    </span>
                                    <button onclick="updateQuantity({{ $item->id }}, 1)" class="bg-gray-200 text-gray-700 px-2 py-1 rounded">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $item->keterangan ?: '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="removeItem({{ $item->id }})" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash-alt mr-1"></i>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Submit Form -->
        <div class="mt-8">
            <form id="submitForm" action="{{ route('admin.usulan.cart.submit') }}" method="POST" class="bg-white shadow rounded-lg p-6">
                @csrf
                <div class="mb-6">
                    <label for="bidang" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-building mr-1"></i>
                        Bidang <span class="text-red-500">*</span>
                    </label>
                    <select name="bidang" id="bidang" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Bidang</option>
                        <option value="umum">Umum</option>
                        <option value="keuangan">Keuangan</option>
                        <option value="sdm">SDM</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.usulan.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Ajukan Usulan
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

@push('scripts')
<script>
function updateQuantity(id, change) {
    const quantityElement = document.getElementById(`quantity-${id}`);
    const currentQuantity = parseInt(quantityElement.textContent);
    const newQuantity = currentQuantity + change;

    if (newQuantity < 1) return;

    fetch(`{{ url('admin/usulan-pengadaan/cart') }}/${id}/update`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            jumlah: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            quantityElement.textContent = newQuantity;
        } else {
            alert(data.message || 'Gagal memperbarui jumlah');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui jumlah');
    });
}

function removeItem(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) return;

    fetch(`{{ url('admin/usulan-pengadaan/cart') }}/${id}`, {
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
            alert(data.message || 'Gagal menghapus item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus item');
    });
}

document.getElementById('submitForm').addEventListener('submit', function(e) {
    const bidang = document.getElementById('bidang').value;
    if (!bidang) {
        e.preventDefault();
        alert('Silakan pilih bidang terlebih dahulu');
    }
});
</script>
@endpush
