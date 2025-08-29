@extends('layouts.admin')

@section('title', 'Edit Monitoring')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Monitoring Barang') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">Edit Data Monitoring - {{ $monitoring->id_monitoring }}</h3>
                    <a href="{{ route('admin.monitoring') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong>Whoops!</strong> Ada beberapa masalah dengan inputan Anda.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.monitoring.update', $monitoring->id_monitoring) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="tanggal" id="tanggal"
                                   value="{{ old('tanggal', $monitoring->tanggal->format('Y-m-d\TH:i')) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Barang -->
                        <div>
                            <label for="id_barang" class="block text-sm font-medium text-gray-700">
                                Barang <span class="text-red-500">*</span>
                            </label>
                            <select name="id_barang" id="id_barang"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->id_barang }}"
                                            data-stok="{{ $item->stok }}"
                                            {{ old('id_barang', $monitoring->id_barang) == $item->id_barang ? 'selected' : '' }}>
                                        {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_barang')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keperluan -->
                        <div>
                            <label for="keperluan" class="block text-sm font-medium text-gray-700">
                                Keperluan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="keperluan" id="keperluan"
                                   value="{{ old('keperluan', $monitoring->keperluan) }}"
                                   placeholder="Masukkan keperluan..."
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('keperluan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pengambil -->
                        <div>
                            <label for="pengambil" class="block text-sm font-medium text-gray-700">
                                Pengambil <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pengambil" id="pengambil"
                                   value="{{ old('pengambil', $monitoring->pengambil) }}"
                                   placeholder="Nama pengambil..."
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('pengambil')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Debit -->
                        <div>
                            <label for="debit" class="block text-sm font-medium text-gray-700">
                                Debit (Penambahan Stok)
                            </label>
                            <input type="number" name="debit" id="debit" min="0"
                                   value="{{ old('debit', $monitoring->debit) }}"
                                   placeholder="0"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('debit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kredit -->
                        <div>
                            <label for="kredit" class="block text-sm font-medium text-gray-700">
                                Kredit (Pengurangan Stok)
                            </label>
                            <input type="number" name="kredit" id="kredit" min="0"
                                   value="{{ old('kredit', $monitoring->kredit) }}"
                                   placeholder="0"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('kredit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500" id="stok-info">Pilih barang untuk melihat stok tersedia</p>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                                  placeholder="Keterangan tambahan (opsional)..."
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('keterangan', $monitoring->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Values Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-700 mb-2">Informasi Saat Ini:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Saldo Saat Ini:</span>
                                <span class="font-semibold">{{ number_format($monitoring->saldo) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Debit Awal:</span>
                                <span class="font-semibold text-green-600">{{ number_format($monitoring->debit) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Kredit Awal:</span>
                                <span class="font-semibold text-red-600">{{ number_format($monitoring->kredit) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.monitoring') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const barangSelect = document.getElementById('id_barang');
    const kreditInput = document.getElementById('kredit');
    const stokInfo = document.getElementById('stok-info');

    function updateStokInfo() {
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        if (selectedOption.value) {
            const stok = selectedOption.getAttribute('data-stok');
            stokInfo.textContent = `Stok tersedia: ${stok}`;
            stokInfo.className = 'mt-1 text-sm text-blue-600';
        } else {
            stokInfo.textContent = 'Pilih barang untuk melihat stok tersedia';
            stokInfo.className = 'mt-1 text-sm text-gray-500';
        }
    }

    barangSelect.addEventListener('change', updateStokInfo);

    // Initial check
    updateStokInfo();
});
</script>
@endsection
