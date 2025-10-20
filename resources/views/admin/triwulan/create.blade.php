@extends('layouts.admin')

@section('title', 'Tambah Data Triwulan')

@section('header')
SISTEM MONITORING BARANG HABIS PAKAI
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-2xl mx-auto">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <a href="{{ route('admin.triwulan.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>

                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Tambah Data Triwulan</h2>
                    <p class="mt-1 text-sm text-gray-600">Generate data triwulan berdasarkan periode tertentu</p>
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

                <form action="{{ route('admin.triwulan.generate') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Tahun -->
                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700">
                            Tahun
                        </label>
                        <select name="tahun" id="tahun" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tahun') border-red-500 @enderror">
                            <option value="">Pilih Tahun</option>
                            @for ($year = 2020; $year <= 2030; $year++) <option value="{{ $year }}" {{
                                old('tahun')==$year ? 'selected' : '' }}>
                                {{ $year }}
                                </option>
                                @endfor
                        </select>
                        @error('tahun')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Triwulan -->
                    <div>
                        <label for="triwulan" class="block text-sm font-medium text-gray-700">
                            Triwulan
                        </label>
                        <select name="triwulan" id="triwulan" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('triwulan') border-red-500 @enderror">
                            <option value="">Pilih Triwulan</option>
                            <option value="1" {{ old('triwulan')=='1' ? 'selected' : '' }}>
                                Triwulan 1 (Januari - Maret)
                            </option>
                            <option value="2" {{ old('triwulan')=='2' ? 'selected' : '' }}>
                                Triwulan 2 (April - Juni)
                            </option>
                            <option value="3" {{ old('triwulan')=='3' ? 'selected' : '' }}>
                                Triwulan 3 (Juli - September)
                            </option>
                            <option value="4" {{ old('triwulan')=='4' ? 'selected' : '' }}>
                                Triwulan 4 (Oktober - Desember)
                            </option>
                        </select>
                        @error('triwulan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informasi -->
                    <div class="rounded-lg bg-blue-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                <p class="text-sm text-blue-700">
                                    Data triwulan akan digenerate berdasarkan data detail monitoring barang pada periode
                                    yang dipilih.
                                    Sistem akan menghitung saldo awal, total debit, kredit, dan persediaan secara
                                    otomatis.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                        <a href="{{ route('admin.triwulan.index') }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Batal
                        </a>

                        <button type="submit"
                            class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-6 py-2 text-sm font-semibold tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Generate Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
