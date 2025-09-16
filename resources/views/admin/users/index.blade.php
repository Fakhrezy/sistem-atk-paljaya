@extends('layouts.admin')

@section('title', 'Data Users')

@section('header')
    SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<script>
function deleteUser(url) {
    Swal.fire({
        title: 'Hapus User?',
        text: 'Apakah Anda yakin ingin menghapus user ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6B7280',
        cancelButtonColor: '#dc2626',
        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Menghapus User...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);

            // Add method override
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

    <div class="h-full">
        <div class="max-w-full">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="w-full p-6 text-gray-900">
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-800">Daftar Users</h2>

                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="relative p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500 rounded">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="relative p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500 rounded">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1 max-w-2xl">
                            <form action="{{ route('admin.users') }}" method="GET" class="flex gap-2">
                                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                                <div class="flex-1">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                           placeholder="Cari nama atau email..."
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div class="w-44">
                                    <select name="role" class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <option value="">Semua Role</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                                @if(request('search') || request('role'))
                                    <a href="{{ route('admin.users', ['per_page' => request('per_page', 10)]) }}"
                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Reset
                                    </a>
                                @endif
                            </form>
                        </div>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.users.create') }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest text-green-600 transition duration-150 ease-in-out bg-white border border-green-500 rounded-md shadow-sm hover:bg-green-50 focus:bg-green-50 active:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah User
                            </a>
                        </div>
                    </div>

                    <div class="w-full bg-white rounded-lg shadow-md">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">ID</th>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Nama</th>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Email</th>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Role</th>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Bidang</th>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Bergabung</th>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-900 uppercase border border-gray-300">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                <tr class="transition-colors duration-200 ease-in-out hover:bg-gray-50">
                                    <td class="px-3 py-4 text-sm font-medium text-gray-900 border border-gray-300">
                                        <span class="font-mono text-sm">{{ $user->id }}</span>
                                    </td>
                                    <td class="px-3 py-4 border border-gray-300">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-3 py-4 border border-gray-300">
                                        <div class="text-sm text-gray-600 break-all">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-3 py-4 border border-gray-300">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 border border-gray-300">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($user->bidang === 'teknik')
                                                bg-green-100 text-green-800
                                            @elseif($user->bidang === 'pemasaran')
                                                bg-purple-100 text-purple-800
                                            @elseif($user->bidang === 'keuangan')
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($user->bidang) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 border border-gray-300">
                                        <span class="text-sm text-gray-600">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 text-sm font-medium border border-gray-300">
                                        <div class="flex gap-1">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="px-2 py-1 text-xs text-white transition duration-150 bg-blue-600 rounded hover:bg-blue-700"
                                               title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <button onclick="deleteUser('{{ route('admin.users.destroy', $user) }}')"
                                                        class="px-2 py-1 text-xs text-white transition duration-150 bg-gray-500 rounded hover:bg-gray-600"
                                                        title="Hapus User">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <p class="mt-2 text-base font-medium text-gray-500">Belum ada data user</p>
                                            <p class="mt-1 text-sm text-gray-400">Silakan tambahkan user baru menggunakan tombol di atas</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        <div class="flex items-center mb-4 space-x-2">
                            <span class="text-sm text-gray-700">Tampilkan</span>
                            <select name="per_page"
                                    onchange="window.location.href = '{{ route('admin.users') }}?per_page=' + this.value + '&search={{ request('search') }}&role={{ request('role') }}'"
                                    class="text-sm border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                @foreach([10, 25, 50, 100] as $perPage)
                                    <option value="{{ $perPage }}" {{ request('per_page', 10) == $perPage ? 'selected' : '' }}>
                                        {{ $perPage }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-sm text-gray-700">item per halaman</span>
                        </div>
                        <div>
                            {{ $users->appends(['per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
