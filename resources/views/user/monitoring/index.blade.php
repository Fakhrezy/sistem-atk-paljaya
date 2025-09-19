@extends('layouts.user')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <div class="p-6 bg-white rounded-lg shadow-lg">
        <h2 class="mb-6 text-2xl font-bold">Monitoring Barang</h2>

        @if(session('success'))
        <div class="p-4 mb-6 text-green-700 bg-green-100 border-l-4 border-green-500" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama Barang</th>
                        <th class="px-4 py-3 text-left">Stok</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($monitorings as $monitoring)
                    <tr>
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ $monitoring->barang->nama_barang }}</td>
                        <td class="px-4 py-3">{{ $monitoring->saldo ?? 0 }}</td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-{{ $monitoring->status == 'Disetujui' ? 'green' : 'red' }}-600">
                                {{ $monitoring->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('user.monitoring.show', $monitoring->id) }}"
                               class="px-3 py-1 text-sm font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                            Tidak ada data monitoring barang
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
