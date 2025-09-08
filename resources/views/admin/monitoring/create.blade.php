@extends('layouts.admin')

@section('title', 'Tambah Monitoring')

@section('header')
    SISTEM INFORMASI MONITORING BARANG ATK CETAKAN & TINTA
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">Tambah Data Monitoring</h3>
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

                <form action="{{ route('admin.monitoring.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="tanggal" id="tanggal"
                                   value="{{ old('tanggal', now()->format('Y-m-d\TH:i')) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('tanggal') border-red-500 @enderror">
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
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('id_barang') border-red-500 @enderror">
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->id_barang }}"
                                            data-stok="{{ $item->stok }}"
                                            {{ old('id_barang') == $item->id_barang ? 'selected' : '' }}>
                                        {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_barang')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bidang -->
                        <div>
                            <label for="bidang" class="block text-sm font-medium text-gray-700">
                                Bidang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="bidang" id="bidang"
                                   value="{{ auth()->user()->bidang }}"
                                   readonly
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-600 cursor-not-allowed">
                            <p class="mt-1 text-sm text-blue-600">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Bidang otomatis terisi berdasarkan profil Anda
                            </p>
                        </div>

                        <!-- Pengambil -->
                        <div>
                            <label for="pengambil" class="block text-sm font-medium text-gray-700">
                                Pengambil <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pengambil" id="pengambil"
                                   value="{{ old('pengambil') }}"
                                   placeholder="Nama pengambil..."
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('pengambil') border-red-500 @enderror">
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
                                   value="{{ old('debit') }}"
                                   placeholder="0"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('debit') border-red-500 @enderror">
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
                                   value="{{ old('kredit') }}"
                                   placeholder="0"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('kredit') border-red-500 @enderror">
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
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.monitoring') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Simpan
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

    function validateKredit() {
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        if (selectedOption.value && kreditInput.value) {
            const stok = parseInt(selectedOption.getAttribute('data-stok'));
            const kredit = parseInt(kreditInput.value);

            if (kredit > stok) {
                kreditInput.setCustomValidity('Kredit tidak boleh lebih dari stok tersedia');
                stokInfo.textContent = `Kredit melebihi stok tersedia (${stok})`;
                stokInfo.className = 'mt-1 text-sm text-red-600';
            } else {
                kreditInput.setCustomValidity('');
                updateStokInfo();
            }
        }
    }

    barangSelect.addEventListener('change', updateStokInfo);
    kreditInput.addEventListener('input', validateKredit);

    // Initial check
    updateStokInfo();
});
</script>
@endsection
