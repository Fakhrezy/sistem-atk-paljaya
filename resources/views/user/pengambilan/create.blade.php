@extends('layouts.user')

@section('title', 'Ambil Barang')

@section('header')
    Ambil Barang
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <a href="{{ route('user.pengambilan.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>

                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Form Pengambilan Barang</h2>
                    <p class="mt-1 text-sm text-gray-600">Isi form di bawah untuk mengambil barang</p>
                </div>

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Informasi Barang -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Barang</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Foto Barang -->
                        <div>
                            @if($barang->foto)
                                <div class="aspect-w-1 aspect-h-1 w-full h-48 bg-gray-200 rounded-lg overflow-hidden">
                                    <img src="{{ asset('storage/'.$barang->foto) }}"
                                         alt="{{ $barang->nama_barang }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="flex items-center justify-center h-48 bg-gray-200 rounded-lg">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Detail Barang -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $barang->nama_barang }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis</label>
                                <p class="mt-1 text-gray-900">{{ ucfirst($barang->jenis) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Satuan</label>
                                <p class="mt-1 text-gray-900">{{ $barang->satuan }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stok Tersedia</label>
                                <p class="mt-1 text-lg font-bold
                                    @if($barang->stok > 10) text-green-600
                                    @elseif($barang->stok > 5) text-yellow-600
                                    @else text-red-600
                                    @endif">
                                    {{ $barang->stok }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Pengambilan -->
                <form action="{{ route('user.pengambilan.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="barang_id" value="{{ $barang->id_barang }}">

                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="jumlah"
                               id="jumlah"
                               min="1"
                               max="{{ $barang->stok }}"
                               value="{{ old('jumlah', 1) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('jumlah') border-red-500 @enderror"
                               required>
                        @error('jumlah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Maksimal: {{ $barang->stok }} {{ $barang->satuan }}</p>
                    </div>

                    <div>
                        <label for="bidang" class="block text-sm font-medium text-gray-700">
                            Bidang <span class="text-red-500">*</span>
                        </label>
                        <select name="bidang"
                                id="bidang"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('bidang') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Bidang</option>
                            <option value="umum" {{ old('bidang', auth()->user()->bidang) == 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="perencanaan" {{ old('bidang', auth()->user()->bidang) == 'perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                            <option value="keuangan" {{ old('bidang', auth()->user()->bidang) == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                            <option value="operasional" {{ old('bidang', auth()->user()->bidang) == 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="lainnya" {{ old('bidang', auth()->user()->bidang) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('bidang')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700">
                            Keterangan
                        </label>
                        <textarea name="keterangan"
                                  id="keterangan"
                                  rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('keterangan') border-red-500 @enderror"
                                  placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('user.pengambilan.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Batal
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Ambil Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
