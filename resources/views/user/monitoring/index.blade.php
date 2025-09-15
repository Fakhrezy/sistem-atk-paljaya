@extends('layouts.user')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Monitoring Barang</h2>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Nama Barang</th>
                        <th class="py-3 px-4 text-left">Stok</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($monitorings as $monitoring)
                    <tr>
                        <td class="py-3 px-4">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4">
                            @if($monitoring->barang)
                                {{ $monitoring->barang->nama_barang }}
                            @else
                                <span class="text-red-500">Data barang tidak ditemukan</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $monitoring->stok }}</td>
                        <td class="py-3 px-4">
                            @if($monitoring->stok <= $monitoring->min_stok)
                                <span class="text-red-600 font-semibold">Stok Minimum</span>
                            @else
                                <span class="text-green-600">Stok Aman</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($monitoring->stok <= $monitoring->min_stok && $monitoring->barang)
                            <a href="{{ route('user.pengadaan.create', $monitoring->id) }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                Usulkan Pengadaan
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
