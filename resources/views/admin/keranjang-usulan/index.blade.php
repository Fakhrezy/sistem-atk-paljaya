@extends('layouts.admin')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <div class="p-6 bg-white rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Keranjang Usulan</h2>
            <a href="{{ route('admin.katalog-usulan.index') }}" class="text-blue-600 hover:text-blue-800">
                Kembali ke Katalog
            </a>
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

        @if($keranjang->isEmpty())
        <div class="py-8 text-center">
            <p class="text-gray-600">Keranjang usulan masih kosong</p>
            <a href="{{ route('admin.katalog-usulan.index') }}"
                class="inline-block mt-2 text-blue-600 hover:text-blue-800">
                Lihat Katalog
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama Barang</th>
                        <th class="px-4 py-3 text-left">Satuan</th>
                        <th class="px-4 py-3 text-left">Jumlah</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($keranjang as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ $item->barang->nama_barang }}</td>
                        <td class="px-4 py-3">{{ $item->barang->satuan }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.keranjang-usulan.update', $item->id) }}" method="POST"
                                class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="1"
                                    class="w-20 px-2 py-1 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.keranjang-usulan.remove', $item->id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800"
                                    onclick="confirmDelete(event, this)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-6">
            <form action="{{ route('admin.pengadaan.store-batch') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-6 py-2 text-white transition duration-200 bg-blue-500 rounded hover:bg-blue-600">
                    Ajukan Usulan
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(event, form) {
    event.preventDefault();

    Swal.fire({
        title: 'Hapus Item?',
        text: 'Apakah Anda yakin ingin menghapus item ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times"></i> Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            form.submit();
        }
    });
}
</script>
@endpush
