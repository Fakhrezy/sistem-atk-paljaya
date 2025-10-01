@extends('layouts.admin')

@section('title', 'Ambil Barang')

@section('header')
Ambil Barang
@endsection

@section('content')
<div class="h-full">
    <div class="mx-auto max-w-2xl">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <a href="{{ route('admin.pengambilan.index') }}"
                        class="inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:bg-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>

                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Form Pengambilan Barang</h2>
                    <p class="mt-1 text-sm text-gray-600">Isi form di bawah untuk mengambil barang</p>
                </div>

                @if ($errors->any())
                <div class="mb-4 rounded border-l-4 border-red-500 bg-red-100 p-4 text-red-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Informasi Barang -->
                <div class="mb-6 rounded-lg bg-gray-50 p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800">Informasi Barang</h3>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Foto Barang -->
                        <div>
                            @if ($barang->foto)
                            <div class="aspect-w-1 aspect-h-1 h-48 w-full overflow-hidden rounded-lg bg-gray-200">
                                <img src="{{ asset('storage/' . $barang->foto) }}" alt="{{ $barang->nama_barang }}"
                                    class="h-full w-full object-cover">
                            </div>
                            @else
                            <div class="flex h-48 items-center justify-center rounded-lg bg-gray-200">
                                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
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
                                <p class="@if ($barang->stok > 10) text-green-600
                                    @elseif($barang->stok > 5) text-yellow-600
                                    @else text-red-600 @endif mt-1 text-lg font-bold">
                                    {{ $barang->stok }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Pengambilan -->
                <form action="{{ route('admin.pengambilan.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="barang_id" value="{{ $barang->id_barang }}">

                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah" id="jumlah" min="1" max="{{ $barang->stok }}"
                            value="{{ old('jumlah', 1) }}"
                            class="@error('jumlah') border-red-500 @enderror mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
                        <select name="bidang" id="bidang"
                            class="@error('bidang') border-red-500 @enderror mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                            <option value="">Pilih Bidang</option>
                            <option value="umum" {{ old('bidang', auth()->user()->bidang) == 'umum' ? 'selected' : ''
                                }}>Umum</option>
                            <option value="perencanaan" {{ old('bidang', auth()->user()->bidang) == 'perencanaan' ?
                                'selected' : '' }}>
                                Perencanaan</option>
                            <option value="keuangan" {{ old('bidang', auth()->user()->bidang) == 'keuangan' ? 'selected'
                                : '' }}>Keuangan
                            </option>
                            <option value="operasional" {{ old('bidang', auth()->user()->bidang) == 'operasional' ?
                                'selected' : '' }}>
                                Operasional</option>
                            <option value="lainnya" {{ old('bidang', auth()->user()->bidang) == 'lainnya' ? 'selected' :
                                '' }}>Lainnya
                            </option>
                        </select>
                        @error('bidang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="@error('keterangan') border-red-500 @enderror mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                        <a href="{{ route('admin.pengambilan.index') }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Batal
                        </a>

                        <button type="submit"
                            class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-6 py-2 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
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
