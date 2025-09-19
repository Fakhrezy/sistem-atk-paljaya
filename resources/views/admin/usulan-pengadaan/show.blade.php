@extends('layouts.app')

@section('title', 'Detail Usulan Pengadaan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.usulan-pengadaan.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Kembali ke Daftar Usulan
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Usulan Pengadaan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                        <p class="text-base">{{ $usulan->nama_barang }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                        <p class="text-base">{{ $usulan->jenis }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <p class="text-base">{{ $usulan->jumlah }} {{ $usulan->satuan }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bidang</label>
                        <p class="text-base">{{ $usulan->bidang }}</p>
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="px-2 py-1 inline-flex text-sm font-semibold rounded-full
                            @switch($usulan->status)
                                @case('diajukan') bg-yellow-100 text-yellow-800 @break
                                @case('diproses') bg-blue-100 text-blue-800 @break
                                @case('diterima') bg-green-100 text-green-800 @break
                                @case('ditolak') bg-red-100 text-red-800 @break
                            @endswitch
                        ">
                            {{ ucfirst($usulan->status) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pengusul</label>
                        <p class="text-base">{{ $usulan->user->name }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan</label>
                        <p class="text-base">{{ $usulan->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    @if($usulan->processed_by)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diproses Oleh</label>
                        <p class="text-base">{{ $usulan->processor->name }} ({{ $usulan->processed_at->format('d/m/Y H:i') }})</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <p class="text-base">{{ $usulan->keterangan ?? '-' }}</p>
            </div>

            @if($usulan->status === 'diajukan')
            <div class="mt-6 flex space-x-4">
                <button onclick="showUpdateStatusModal({{ $usulan->id }})" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Update Status
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 z-10 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="updateStatusForm">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Update Status Usulan
                            </h3>
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="modal-status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="diproses">Diproses</option>
                                    <option value="diterima">Diterima</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <textarea id="modal-keterangan" name="keterangan" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Update
                    </button>
                    <button type="button" onclick="closeUpdateStatusModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentUsulanId = null;

function showUpdateStatusModal(id) {
    currentUsulanId = id;
    document.getElementById('updateStatusModal').classList.remove('hidden');
}

function closeUpdateStatusModal() {
    currentUsulanId = null;
    document.getElementById('updateStatusModal').classList.add('hidden');
}

document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!currentUsulanId) return;

    const status = document.getElementById('modal-status').value;
    const keterangan = document.getElementById('modal-keterangan').value;

    fetch(`/admin/usulan-pengadaan/${currentUsulanId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status, keterangan })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui status');
    });
});
</script>
@endpush
