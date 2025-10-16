@extends('layouts.admin')

@section('title', 'Tambah Barang')

@section('header')
    SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan</label>
                            <input type="text" name="satuan" id="satuan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="harga_barang" class="block text-sm font-medium text-gray-700">Harga Barang</label>
                            <input type="number" name="harga_barang" id="harga_barang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                            <input type="number" name="stok" id="stok" value="0" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis</label>
                            <select name="jenis" id="jenis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="atk">ATK</option>
                                <option value="cetak">Cetak</option>
                                <option value="tinta">Tinta</option>
                            </select>
                        </div>

                        <div>
                            <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Barang</label>
                            <div class="mt-1 px-6 pt-5 pb-6 border border-gray-300 rounded-lg" id="file-drop-area">
                                <div class="space-y-1 text-center">
                                    <div id="file-preview" class="hidden">
                                        <img id="preview-image" class="mx-auto h-32 w-32 object-cover rounded-lg shadow-md" src="" alt="Preview">
                                        <button type="button" id="remove-image" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus Gambar
                                        </button>
                                    </div>
                                    <div id="upload-prompt">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="text-sm text-gray-600">
                                            <label for="foto" class="cursor-pointer text-gray-700 hover:text-gray-800">
                                                <span class="underline">Pilih file foto</span>
                                                <input id="foto" name="foto" type="file" class="sr-only" accept="image/*">
                                            </label>
                                            <p class="mt-1 text-gray-500">atau drag and drop file ke area ini</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.barang') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('foto');
            const dropArea = document.getElementById('file-drop-area');
            const uploadPrompt = document.getElementById('upload-prompt');
            const filePreview = document.getElementById('file-preview');
            const previewImage = document.getElementById('preview-image');
            const removeButton = document.getElementById('remove-image');

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop area when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });

            // Handle dropped files
            dropArea.addEventListener('drop', handleDrop, false);

            // Handle file input change
            fileInput.addEventListener('change', function(e) {
                handleFiles(e.target.files);
            });

            // Remove image button
            removeButton.addEventListener('click', function() {
                fileInput.value = '';
                hidePreview();
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight(e) {
                dropArea.classList.add('border-indigo-500', 'bg-indigo-50');
            }

            function unhighlight(e) {
                dropArea.classList.remove('border-indigo-500', 'bg-indigo-50');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                handleFiles(files);
            }

            function handleFiles(files) {
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            showPreview(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }

            function showPreview(src) {
                previewImage.src = src;
                uploadPrompt.classList.add('hidden');
                filePreview.classList.remove('hidden');
            }

            function hidePreview() {
                uploadPrompt.classList.remove('hidden');
                filePreview.classList.add('hidden');
                previewImage.src = '';
            }
        });
    </script>
@endsection
