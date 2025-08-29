@extends('layouts.admin')

@section('title', 'Edit Barang')

@section('header')
    Edit Barang
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.barang.update', $barang) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang" value="{{ $barang->nama_barang }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan</label>
                            <input type="text" name="satuan" id="satuan" value="{{ $barang->satuan }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="harga_barang" class="block text-sm font-medium text-gray-700">Harga Barang</label>
                            <input type="number" name="harga_barang" id="harga_barang" value="{{ $barang->harga_barang }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                            <input type="number" name="stok" id="stok" value="{{ $barang->stok }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis</label>
                            <select name="jenis" id="jenis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="atk" {{ $barang->jenis == 'atk' ? 'selected' : '' }}>ATK</option>
                                <option value="cetak" {{ $barang->jenis == 'cetak' ? 'selected' : '' }}>Cetak</option>
                                <option value="tinta" {{ $barang->jenis == 'tinta' ? 'selected' : '' }}>Tinta</option>
                            </select>
                        </div>

                        <div>
                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                            @if($barang->foto)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$barang->foto) }}" alt="Current Image" class="h-32 w-32 object-cover rounded">
                                </div>
                            @endif
                            <input type="file" name="foto" id="foto" class="mt-1 block w-full" accept="image/*">
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.barang') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
