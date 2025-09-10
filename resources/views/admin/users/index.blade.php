@extends('layouts.admin')

@section('title', 'Data Users')

@section('header')
    SISTEM INFORMASI MONITORING BARANG ATK, CETAKAN & TINTA
@endsection

@section('content')

    <div class="h-full">
        <div class="max-w-full">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 w-full">
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-800">Daftar Users</h2>
                                <p class="mt-1 text-sm text-gray-600">Kelola data pengguna sistem</p>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded relative">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded relative">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="mb-4 flex justify-between items-center">
                        <div class="flex-1 max-w-2xl">
                            <form action="{{ route('admin.users') }}" method="GET" class="flex gap-2">
                                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                                <div class="flex-1">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                           placeholder="Cari nama atau email..."
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div class="w-44">
                                    <select name="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <option value="">Semua Role</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                                @if(request('search') || request('role'))
                                    <a href="{{ route('admin.users', ['per_page' => request('per_page', 10)]) }}"
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Reset
                                    </a>
                                @endif
                            </form>
                        </div>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.users.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-white border border-green-500 rounded-md font-semibold text-sm text-green-600 tracking-widest hover:bg-green-50 focus:bg-green-50 active:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah User
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md w-full">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">ID</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Nama</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Email</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Role</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Bidang</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Bergabung</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider border border-gray-300">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors duration-200 ease-in-out">
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
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-1 sm:space-y-0 sm:space-x-2">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="group inline-flex items-center px-2 py-1 border border-indigo-300 text-indigo-600 hover:bg-indigo-50 rounded-md text-xs font-medium transition-colors duration-150 ease-in-out">
                                                <svg class="w-3 h-3 mr-1 text-indigo-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
                                                      action="{{ route('admin.users.destroy', $user) }}"
                                                      method="POST"
                                                      class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="group inline-flex items-center px-2 py-1 border border-red-300 text-red-600 hover:bg-red-50 rounded-md text-xs font-medium transition-colors duration-150 ease-in-out">
                                                        <svg class="w-3 h-3 mr-1 text-red-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
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
                                            <p class="mt-2 text-gray-500 text-base font-medium">Belum ada data user</p>
                                            <p class="mt-1 text-gray-400 text-sm">Silakan tambahkan user baru menggunakan tombol di atas</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        <div class="flex items-center space-x-2 mb-4">
                            <span class="text-sm text-gray-700">Tampilkan</span>
                            <select name="per_page"
                                    onchange="window.location.href = '{{ route('admin.users') }}?per_page=' + this.value + '&search={{ request('search') }}&role={{ request('role') }}'"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
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
