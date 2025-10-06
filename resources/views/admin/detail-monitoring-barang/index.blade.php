@extends('layouts.admin')

@section('title', 'Detail Monitoring Barang')

@section('header')
SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
@endsection

@section('content')
<div class="h-full">
    <div class="max-w-full">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Detail Monitoring Barang</h2>
                            <p class="mt-1 text-sm text-gray-600">Rekapitulasi monitoring pengambilan dan pengadaan
                                barang</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Sync Button -->
                            <button onclick="syncData()"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest text-blue-600 transition duration-150 ease-in-out bg-white border border-blue-500 rounded-md shadow-sm hover:bg-blue-50 focus:bg-blue-50 active:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-blue-600"
                                    fill="currentColor" viewBox="0 0 512 512">
                                    <path
                                        d="M0 0 C6.53769539 4.48202828 9.76804331 10.96023882 12.8359375 18.05859375 C13.25955566 19.01451416 13.68317383 19.97043457 14.11962891 20.95532227 C18.10465928 30.01475887 21.92345729 39.1468369 25.72583008 48.28417969 C31.53077628 62.23051117 37.46058356 76.12333293 43.39300537 90.01583862 C44.68217914 93.03491353 45.97004406 96.05454421 47.2578125 99.07421875 C47.64887177 99.98843094 48.03993103 100.90264313 48.44284058 101.84455872 C48.80169342 102.68637192 49.16054626 103.52818512 49.53027344 104.39550781 C49.84414001 105.13029358 50.15800659 105.86507935 50.48138428 106.62213135 C53.05826897 112.92226395 53.7569388 120.5365148 51.3671875 127.015625 C48.19417751 132.33448469 44.10113303 136.2390025 38.4609375 138.87109375 C24.66642425 141.84638092 14.40689407 134.08641132 2.8984375 127.43359375 C1.61610153 126.69754004 0.33355084 125.96186027 -0.94921875 125.2265625 C-10.17996479 119.92299222 -10.17996479 119.92299222 -13.7265625 117.55859375 C-15.85892253 160.83760478 4.98982026 202.20022017 33.2734375 233.55859375 C33.94890625 234.30753906 34.624375 235.05648437 35.3203125 235.828125 C41.33012058 242.13328689 48.29113529 247.38590812 55.2734375 252.55859375 C55.85480469 252.98962402 56.43617188 253.4206543 57.03515625 253.86474609 C91.89659334 279.2959686 138.26586582 289.78356039 180.8359375 283.43359375 C201.22816586 280.03243357 220.69050092 272.95667829 238.83520508 263.13525391 C245.46154984 259.64696855 251.48063412 258.93425756 258.70703125 260.578125 C264.16052624 262.6615759 268.48340448 266.60560934 271.5 271.58203125 C274.69499665 279.13655605 273.57590217 285.62746731 270.83203125 293.06640625 C266.52215419 299.95788887 259.30893758 303.46193117 252.2109375 306.93359375 C251.24776611 307.40692139 250.28459473 307.88024902 249.29223633 308.36791992 C197.52893117 333.26699127 138.1255901 336.18616522 83.82104492 317.84204102 C59.15148428 309.08449012 35.90421791 295.94962069 16.2734375 278.55859375 C15.46390625 277.85734375 14.654375 277.15609375 13.8203125 276.43359375 C2.39861728 266.37675519 -7.70003608 255.79824504 -16.7265625 243.55859375 C-17.50902344 242.49769531 -18.29148438 241.43679688 -19.09765625 240.34375 C-52.77953436 193.11907168 -65.25203976 134.56596484 -55.7265625 77.55859375 C-51.87627335 56.93315998 -45.34905333 37.4387538 -36.1015625 18.62109375 C-35.65852783 17.71528564 -35.21549316 16.80947754 -34.7590332 15.8762207 C-30.47248813 7.45913451 -26.38724846 1.37781043 -17.23828125 -1.78125 C-10.90432465 -3.44600687 -5.98502197 -2.50302963 0 0 Z"
                                        transform="translate(100.7265625,140.44140625)" />
                                    <path
                                        d="M0 0 C9.62297208 8.9502492 18.25338441 18.3870131 26 29 C26.77859375 30.05703125 27.5571875 31.1140625 28.359375 32.203125 C61.43727342 78.51218279 73.97816979 135.703206 65.54345703 191.74755859 C61.9056056 213.51910251 55.09935141 234.14947938 45.375 253.9375 C44.93196533 254.84330811 44.48893066 255.74911621 44.0324707 256.68237305 C39.74592563 265.09945924 35.66068596 271.18078332 26.51171875 274.33984375 C20.15844632 276.00967741 14.98785943 275.22838385 9.10546875 272.3125 C-2.9925994 264.77084713 -4.8072197 246.85788217 -8.203125 234.0859375 C-8.69053993 232.26230892 -9.17807049 230.43871124 -9.66571045 228.61514282 C-10.68117697 224.81209728 -11.69241872 221.00795641 -12.70117188 217.203125 C-13.99024276 212.34398218 -15.29422307 207.48897598 -16.60191727 202.63481712 C-17.6115578 198.87746963 -18.6110389 195.11747379 -19.60774994 191.35667801 C-20.08414526 189.56567609 -20.56415438 187.77563112 -21.04795074 185.98661423 C-21.72395811 183.48254165 -22.3855833 180.97506953 -23.04394531 178.46630859 C-23.24337738 177.73983627 -23.44280945 177.01336395 -23.64828491 176.26487732 C-25.68953258 168.35568739 -26.06594942 161.31870896 -22 154 C-18.62709065 149.07350816 -15.03350625 145.28085608 -9 144 C0.47779844 142.65192417 6.93581482 145.16375411 15 150 C16.25296875 150.74830078 16.25296875 150.74830078 17.53125 151.51171875 C19.37347054 152.64364651 21.19031821 153.81674652 23 155 C25.13303021 111.70738687 4.29816662 70.37450464 -24 39 C-25.01320313 37.87658203 -25.01320313 37.87658203 -26.046875 36.73046875 C-32.05794459 30.42398335 -39.02040414 25.17864696 -46 20 C-46.57073242 19.57428711 -47.14146484 19.14857422 -47.72949219 18.70996094 C-69.33000451 2.8414628 -95.51346208 -7.2695017 -122 -11 C-122.96208374 -11.13921875 -122.96208374 -11.13921875 -123.94360352 -11.28125 C-157.11877378 -15.57825367 -189.538768 -9.30627824 -219.82421875 4.265625 C-227.17293326 7.46169685 -233.07074627 8.37975771 -240.6875 5.4375 C-246.0326679 2.80229485 -249.56167417 -1.01460894 -252.13671875 -6.359375 C-254.12758067 -12.44907028 -254.0506835 -19.57329367 -251.375 -25.4375 C-248.14188957 -31.61356993 -241.71645991 -35.00066154 -235.625 -37.8125 C-234.89804932 -38.14870361 -234.17109863 -38.48490723 -233.42211914 -38.83129883 C-155.08430474 -73.91510528 -63.11086847 -57.28198893 0 0 Z"
                                        transform="translate(402,99)" />
                                </svg>
                                Sinkronisasi Data
                            </button>
                            <!-- Export Button -->
                            <a href="{{ route('admin.detail-monitoring-barang.export', request()->query()) }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest transition duration-150 ease-in-out bg-white border rounded-md shadow-sm border-emerald-500 text-emerald-600 hover:bg-emerald-50 focus:bg-emerald-50 active:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-emerald-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Ekspor Data
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="p-4 mb-6 rounded-lg bg-gray-50">
                    <form method="GET" action="{{ route('admin.detail-monitoring-barang.index') }}"
                        class="flex flex-wrap items-end gap-4">

                        <div class="flex-1 min-w-48">
                            <label for="id_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <select id="id_barang" name="id_barang"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Barang</option>
                                @foreach($barangList as $barang)
                                <option value="{{ $barang->id_barang }}" {{ $filters['id_barang']==$barang->id_barang ?
                                    'selected' : '' }}>
                                    {{ $barang->nama_barang }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="min-w-40">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $filters['start_date'] }}"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div class="min-w-40">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $filters['end_date'] }}"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div class="min-w-48">
                            <label for="bidang" class="block text-sm font-medium text-gray-700">Bidang</label>
                            <select id="bidang" name="bidang"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Bidang</option>
                                @foreach($bidangList as $bidang)
                                <option value="{{ $bidang }}" {{ $filters['bidang']==$bidang ? 'selected' : '' }}>
                                    {{ ucfirst($bidang) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="min-w-40">
                            <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
                            <select id="jenis" name="jenis"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Transaksi</option>
                                <option value="debit" {{ $filters['jenis']=='debit' ? 'selected' : '' }}>Debit
                                    (Pengadaan)</option>
                                <option value="kredit" {{ $filters['jenis']=='kredit' ? 'selected' : '' }}>Kredit
                                    (Pengambilan)</option>
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                            @if(array_filter($filters))
                            <a href="{{ route('admin.detail-monitoring-barang.index') }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                </script>
                @endif

                @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: '{{ session('error') }}',
                            confirmButtonColor: '#d33'
                        });
                    });
                </script>
                @endif

                <!-- Data Table -->
                <div class="bg-white rounded-lg shadow">
                    <!-- Mobile view -->
                    <div class="hidden lg:hidden">
                        <div class="p-4 space-y-4">
                            @forelse ($detailMonitoring as $index => $item)
                            <div class="p-4 border rounded-lg bg-gray-50">
                                <!-- Header dengan No dan Tanggal -->
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-white bg-blue-600 rounded-full">
                                            {{ $detailMonitoring->firstItem() + $index }}
                                        </span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{
                                                $item->tanggal->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-600" title="{{ $item->nama_barang }}">
                                                <i class="mr-1 text-gray-500 fas fa-box"></i>
                                                {{ Str::limit($item->nama_barang, 25) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Uraian Section -->
                                <div class="mb-4">
                                    <h4 class="mb-2 text-xs font-semibold text-gray-700 uppercase">Uraian</h4>
                                    <div class="space-y-2 text-xs">
                                        @if($item->keterangan)
                                        <div class="flex items-start space-x-2">
                                            <i class="mt-0.5 text-blue-500 fas fa-sticky-note"></i>
                                            <div>
                                                <span class="font-medium text-gray-600">Keterangan:</span>
                                                <p class="text-gray-700">{{ $item->keterangan }}</p>
                                            </div>
                                        </div>
                                        @endif
                                        @if($item->bidang)
                                        <div class="flex items-center space-x-2">
                                            <i class="text-blue-500 fas fa-building"></i>
                                            <span class="font-medium text-gray-600">Bidang:</span>
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded">
                                                {{ ucfirst($item->bidang) }}
                                            </span>
                                        </div>
                                        @endif
                                        @if($item->pengambil)
                                        <div class="flex items-center space-x-2">
                                            <i class="text-blue-500 fas fa-user"></i>
                                            <span class="font-medium text-gray-600">Pengambil:</span>
                                            <span class="text-gray-700">{{ $item->pengambil }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Persediaan Section -->
                                <div>
                                    <h4 class="mb-2 text-xs font-semibold text-gray-700 uppercase">Persediaan</h4>
                                    <div class="grid grid-cols-3 gap-3 text-center">
                                        <div class="p-2 border border-green-200 rounded bg-green-50">
                                            <div class="text-xs font-medium text-green-700">Debit</div>
                                            <div class="text-sm font-bold text-green-600">
                                                {{ $item->debit ? '+' . number_format($item->debit, 0, ',', '.') : '0'
                                                }}
                                            </div>
                                        </div>
                                        <div class="p-2 border border-red-200 rounded bg-red-50">
                                            <div class="text-xs font-medium text-red-700">Kredit</div>
                                            <div class="text-sm font-bold text-red-600">
                                                {{ $item->kredit ? '-' . number_format($item->kredit, 0, ',', '.') : '0'
                                                }}
                                            </div>
                                        </div>
                                        <div class="p-2 border border-blue-200 rounded bg-blue-50">
                                            <div class="text-xs font-medium text-blue-700">Saldo</div>
                                            <div class="text-sm font-bold text-blue-600">
                                                {{ number_format($item->saldo, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="py-8 text-center text-gray-500">
                                <i class="mb-2 text-3xl text-gray-400 fas fa-chart-line"></i>
                                <p class="text-base font-medium">Belum ada data monitoring</p>
                                <p class="text-sm">Klik "Sinkronisasi Data" untuk memuat data dari monitoring barang dan
                                    pengadaan</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Desktop view -->
                    <div class="block">
                        <table class="w-full border-collapse table-auto">
                            <thead>
                                <!-- Header Utama -->
                                <tr class="bg-gray-100">
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border"
                                        rowspan="2">
                                        No
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border"
                                        rowspan="2">
                                        Tanggal
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border"
                                        rowspan="2">
                                        Nama Barang
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border"
                                        colspan="3">
                                        Uraian
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border"
                                        colspan="3">
                                        Persediaan
                                    </th>
                                </tr>
                                <!-- Sub Header -->
                                <tr class="bg-gray-100">
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Keterangan</th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Bidang
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Pengambil
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Debit
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Kredit
                                    </th>
                                    <th class="px-3 py-3 text-sm font-bold text-center text-gray-700 uppercase border">
                                        Saldo
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($detailMonitoring as $index => $item)
                                <tr class="transition-colors duration-200 hover:bg-gray-50">
                                    <!-- No -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $detailMonitoring->firstItem() + $index }}
                                    </td>
                                    <!-- Tanggal -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $item->tanggal->format('d/m/Y') }}
                                    </td>
                                    <!-- Nama Barang -->
                                    <td class="px-3 py-3 text-sm text-gray-900 border">
                                        {{ $item->barang->nama_barang ?? $item->nama_barang ?? '-' }}
                                    </td>
                                    <!-- Uraian: Keterangan -->
                                    <td class="px-3 py-3 text-sm text-gray-900 border">
                                        @if($item->keterangan)
                                        <div class="flex items-start space-x-2">
                                            <i class="mt-1 text-xs text-blue-500 fas fa-sticky-note"></i>
                                            <span title="{{ $item->keterangan }}">
                                                {{ Str::limit($item->keterangan, 30) }}
                                            </span>
                                        </div>
                                        @else
                                        <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <!-- Uraian: Bidang -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $item->bidang ? ucfirst($item->bidang) : '-' }}
                                    </td>
                                    <!-- Uraian: Pengambil -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $item->pengambil ? Str::limit($item->pengambil, 15) : '-' }}
                                    </td>
                                    <!-- Persediaan: Debit -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $item->debit ? number_format($item->debit, 0, ',', '.') : '0' }}
                                    </td>
                                    <!-- Persediaan: Kredit -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ $item->kredit ? number_format($item->kredit, 0, ',', '.') : '0' }}
                                    </td>
                                    <!-- Persediaan: Saldo -->
                                    <td class="px-3 py-3 text-sm text-center text-gray-900 border">
                                        {{ number_format($item->saldo, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-3 py-8 text-center text-gray-500 border">
                                        <div class="flex flex-col items-center">
                                            <i class="mb-2 text-3xl text-gray-400 fas fa-chart-line"></i>
                                            <p class="text-base font-medium">Belum ada data monitoring</p>
                                            <p class="text-sm">Klik "Sinkronisasi Data" untuk memuat data dari
                                                monitoring barang dan pengadaan</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($detailMonitoring->hasPages())
                <div class="mt-6">
                    {{ $detailMonitoring->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Sync data function
function syncData() {
    Swal.fire({
        title: 'Sinkronisasi Data?',
        text: 'Proses ini akan menyinkronkan data dari monitoring barang dan pengadaan ke detail monitoring.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="mr-2 fas fa-sync-alt"></i>Ya, Sinkronisasi!',
        cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Melakukan Sinkronisasi...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send sync request
            fetch('{{ route('admin.detail-monitoring-barang.sync') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat melakukan sinkronisasi',
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
                });
            });
        }
    });
}
</script>
@endsection