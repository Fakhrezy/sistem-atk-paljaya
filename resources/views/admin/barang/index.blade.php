<x-admin-layout>
    <x-slot name="header">
        {{ __('Data Barang') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 w-full">
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-800">Daftar Barang</h2>
                                <p class="mt-1 text-sm text-gray-600">Kelola data barang ATK, Cetak, dan Tinta</p>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded relative">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('admin.barang.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-white border border-green-500 rounded-md font-semibold text-sm text-green-600 tracking-widest hover:bg-green-50 focus:bg-green-50 active:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Barang
                        </a>
                    </div>

                    <div class="bg-white rounded-lg shadow-md w-full">
                        <table class="w-full table-fixed border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th scope="col" class="w-[12%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">ID Barang</th>
                                    <th scope="col" class="w-[18%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Nama Barang</th>
                                    <th scope="col" class="w-[10%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Satuan</th>
                                    <th scope="col" class="w-[15%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Harga</th>
                                    <th scope="col" class="w-[10%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Jenis</th>
                                    <th scope="col" class="w-[15%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Foto</th>
                                    <th scope="col" class="w-[20%] px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($barang as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-200 ease-in-out">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-300">
                                        <span class="font-mono text-sm">{{ $item->id_barang }}</span>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap border border-gray-300">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_barang }}</div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap border border-gray-300">
                                        <span class="px-2 py-1 text-sm text-gray-600 bg-gray-100 rounded-full">{{ $item->satuan }}</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap border border-gray-300">
                                        <span class="text-sm font-semibold text-green-600">
                                            Rp {{ number_format($item->harga_barang, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap border border-gray-300">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($item->jenis) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap border border-gray-300">
                                        @if($item->foto)
                                            <div class="flex justify-center p-1">
                                                <img src="{{ asset('storage/'.$item->foto) }}"
                                                     alt="{{ $item->nama_barang }}"
                                                     style="width: 90px; height: 90px; object-fit: cover; border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <svg class="mr-1.5 h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                No Image
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium border border-gray-300">
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('admin.barang.edit', $item) }}"
                                               class="group inline-flex items-center px-3 py-1.5 border border-indigo-300 text-indigo-600 hover:bg-indigo-50 rounded-md text-sm font-medium transition-colors duration-150 ease-in-out">
                                                <svg class="w-4 h-4 mr-2 text-indigo-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')"
                                                  action="{{ route('admin.barang.destroy', $item) }}"
                                                  method="POST"
                                                  class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="group inline-flex items-center px-3 py-1.5 border border-red-300 text-red-600 hover:bg-red-50 rounded-md text-sm font-medium transition-colors duration-150 ease-in-out">
                                                    <svg class="w-4 h-4 mr-2 text-red-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="mt-2 text-gray-500 text-base font-medium">Belum ada data barang</p>
                                            <p class="mt-1 text-gray-400 text-sm">Silakan tambahkan barang baru menggunakan tombol di atas</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
