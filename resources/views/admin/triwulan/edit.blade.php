@extends('layouts.admin')

@section('title', 'Edit Data Triwulan')

@section('header')
SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-4xl mx-auto">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Edit Data Triwulan</h2>
                            <p class="text-sm text-gray-600 mt-1">Ubah data laporan triwulan untuk {{
                                $triwulan->nama_barang }}</p>
                        </div>
                        <a href="{{ route('admin.triwulan.index') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                @if($errors->any())
                <div class="p-4 mb-6 text-red-700 bg-red-100 border border-red-300 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Terdapat beberapa kesalahan pada input Anda:
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="pl-5 list-disc space-y-1">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Info Barang -->
                <div class="p-4 mb-6 bg-blue-50 rounded-lg">
                    <h3 class="flex items-center text-sm font-medium text-blue-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi Barang dan Periode
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-blue-600 font-medium">Nama Barang:</span>
                            <p class="text-gray-900">{{ $triwulan->nama_barang }}</p>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Satuan:</span>
                            <p class="text-gray-900">{{ $triwulan->satuan }}</p>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Harga Satuan:</span>
                            <p class="text-gray-900">Rp {{ number_format($triwulan->harga_satuan, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Periode:</span>
                            <p class="text-gray-900">{{ $triwulan->nama_triwulan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Edit -->
                <form action="{{ route('admin.triwulan.update', $triwulan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Saldo Awal -->
                        <div>
                            <label for="saldo_awal_triwulan" class="block text-sm font-medium text-gray-700">
                                Saldo Awal Triwulan <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="saldo_awal_triwulan" id="saldo_awal_triwulan"
                                value="{{ old('saldo_awal_triwulan', $triwulan->saldo_awal_triwulan) }}" min="0"
                                step="1" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0">
                            <p class="mt-1 text-xs text-gray-500">Stok barang pada awal periode triwulan</p>
                        </div>

                        <!-- Total Kredit -->
                        <div>
                            <label for="total_kredit_triwulan" class="block text-sm font-medium text-gray-700">
                                Total Kredit Triwulan <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="total_kredit_triwulan" id="total_kredit_triwulan"
                                value="{{ old('total_kredit_triwulan', $triwulan->total_kredit_triwulan) }}" min="0"
                                step="1" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0">
                            <p class="mt-1 text-xs text-gray-500">Total barang keluar selama triwulan</p>
                        </div>

                        <!-- Total Debit -->
                        <div>
                            <label for="total_debit_triwulan" class="block text-sm font-medium text-gray-700">
                                Total Debit Triwulan <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="total_debit_triwulan" id="total_debit_triwulan"
                                value="{{ old('total_debit_triwulan', $triwulan->total_debit_triwulan) }}" min="0"
                                step="1" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0">
                            <p class="mt-1 text-xs text-gray-500">Total barang masuk selama triwulan</p>
                        </div>
                    </div>

                    <!-- Preview Perhitungan -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="flex items-center text-sm font-medium text-gray-800 mb-3">
                            <i class="fas fa-calculator mr-2"></i>
                            Preview Perhitungan Otomatis
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div class="p-3 bg-white rounded border">
                                <span class="text-gray-600">Total Harga Kredit:</span>
                                <p class="font-medium text-red-600" id="preview_harga_kredit">
                                    Rp {{ number_format($triwulan->total_harga_kredit, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="p-3 bg-white rounded border">
                                <span class="text-gray-600">Total Harga Debit:</span>
                                <p class="font-medium text-green-600" id="preview_harga_debit">
                                    Rp {{ number_format($triwulan->total_harga_debit, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="p-3 bg-white rounded border">
                                <span class="text-gray-600">Total Persediaan:</span>
                                <p class="font-medium text-blue-600" id="preview_persediaan">
                                    {{ number_format($triwulan->total_persediaan_triwulan, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="p-3 bg-white rounded border">
                                <span class="text-gray-600">Total Harga Persediaan:</span>
                                <p class="font-medium text-blue-600" id="preview_harga_persediaan">
                                    Rp {{ number_format($triwulan->total_harga_persediaan, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                        <a href="{{ route('admin.triwulan.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const hargaSatuan = {{ $triwulan->harga_satuan }};
    const saldoAwalInput = document.getElementById('saldo_awal_triwulan');
    const totalKreditInput = document.getElementById('total_kredit_triwulan');
    const totalDebitInput = document.getElementById('total_debit_triwulan');

    const previewHargaKredit = document.getElementById('preview_harga_kredit');
    const previewHargaDebit = document.getElementById('preview_harga_debit');
    const previewPersediaan = document.getElementById('preview_persediaan');
    const previewHargaPersediaan = document.getElementById('preview_harga_persediaan');

    function updatePreview() {
        const saldoAwal = parseInt(saldoAwalInput.value) || 0;
        const totalKredit = parseInt(totalKreditInput.value) || 0;
        const totalDebit = parseInt(totalDebitInput.value) || 0;

        const hargaKredit = hargaSatuan * totalKredit;
        const hargaDebit = hargaSatuan * totalDebit;
        const totalPersediaan = saldoAwal + totalDebit - totalKredit;
        const hargaPersediaan = hargaSatuan * totalPersediaan;

        previewHargaKredit.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaKredit);
        previewHargaDebit.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaDebit);
        previewPersediaan.textContent = new Intl.NumberFormat('id-ID').format(totalPersediaan);
        previewHargaPersediaan.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaPersediaan);
    }

    saldoAwalInput.addEventListener('input', updatePreview);
    totalKreditInput.addEventListener('input', updatePreview);
    totalDebitInput.addEventListener('input', updatePreview);
});
</script>

@endsection
