@extends('layouts.user')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Keranjang Usulan Pengadaan</h2>
            <a href="{{ route('user.katalog-usulan.index') }}" class="text-blue-600 hover:text-blue-800">
                Kembali ke Katalog
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            {{ session('success') }}
        </div>
        @endif

        @if($keranjang->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-600">Keranjang usulan masih kosong</p>
            <a href="{{ route('user.katalog-usulan.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                Lihat Katalog
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Nama Barang</th>
                        <th class="py-3 px-4 text-left">Satuan</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($keranjang as $item)
                    <tr>
                        <td class="py-3 px-4">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4">{{ $item->barang->nama_barang }}</td>
                        <td class="py-3 px-4">{{ $item->barang->satuan }}</td>
                        <td class="py-3 px-4">
                            <form action="{{ route('user.keranjang-usulan.update', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="1"
                                       class="w-20 px-2 py-1 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-4">
                            <form action="{{ route('user.keranjang-usulan.remove', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <form action="{{ route('user.pengadaan.store-batch') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition duration-200">
                    Ajukan Usulan
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
