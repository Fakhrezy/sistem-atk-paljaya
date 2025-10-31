@extends('layouts.admin')

@section('title', 'Data Triwulan')

@section('header')
SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="h-full">
    <div class="max-w-full">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Data Triwulan</h2>

                        </div>
                    </div>
                </div>

                @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                </script>
                @endif

                <!-- Statistics Cards - Horizontal Layout -->
                <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                    <!-- Total Kredit -->
                    <div class="overflow-hidden bg-white rounded-lg shadow-md">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18 12H6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-3">
                                    <dl>
                                        <dt class="text-xs font-medium text-red-700 truncate">Total Kredit</dt>
                                        <dd class="text-lg font-bold text-red-600" id="total-kredit">
                                            {{ isset($statistics['total_kredit']) ?
                                            number_format($statistics['total_kredit'], 0, ',', '.') : '0' }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Debit -->
                    <div class="overflow-hidden bg-white rounded-lg shadow-md">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-3">
                                    <dl>
                                        <dt class="text-xs font-medium text-green-700 truncate">Total Debit</dt>
                                        <dd class="text-lg font-bold text-green-600" id="total-debit">
                                            {{ isset($statistics['total_debit']) ?
                                            number_format($statistics['total_debit'], 0, ',', '.') : '0' }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Persediaan -->
                    <div class="overflow-hidden bg-white rounded-lg shadow-md">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-3">
                                    <dl>
                                        <dt class="text-xs font-medium text-blue-700 truncate">Total Persediaan</dt>
                                        <dd class="text-lg font-bold text-blue-600" id="total-persediaan">
                                            {{ isset($statistics['total_persediaan']) ?
                                            number_format($statistics['total_persediaan'], 0, ',', '.') : '0' }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Harga Debit -->
                    <div class="overflow-hidden bg-white rounded-lg shadow-md">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full"
                                        style="background-color: rgba(52, 211, 153, 0.1);">
                                        <svg class="w-5 h-5" fill="none" stroke="rgb(52, 211, 153)" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-3">
                                    <dl>
                                        <dt class="text-xs font-medium text-emerald-700 truncate">Harga Debit</dt>
                                        <dd class="text-sm font-bold text-emerald-600" id="total-harga-debit">
                                            Rp {{ isset($statistics['total_harga_debit']) ?
                                            number_format($statistics['total_harga_debit'], 0, ',', '.') : '0' }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Harga Persediaan -->
                    <div class="overflow-hidden bg-white rounded-lg shadow-md">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full"
                                        style="background-color: rgba(79, 70, 229, 0.1);">
                                        <svg class="w-5 h-5" fill="none" stroke="rgb(79, 70, 229)" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-3">
                                    <dl>
                                        <dt class="text-xs font-medium text-indigo-700 truncate">Harga Persediaan</dt>
                                        <dd class="text-sm font-bold text-indigo-600" id="total-harga-persediaan">
                                            Rp {{ isset($statistics['total_harga_persediaan']) ?
                                            number_format($statistics['total_harga_persediaan'], 0, ',', '.') : '0' }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="p-4 mb-6 rounded-lg bg-gray-50">
                    <form action="{{ route('admin.triwulan.index') }}" method="GET"
                        class="flex flex-wrap items-end gap-4">
                        <div class="min-w-48">
                            <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Cari nama barang..."
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div class="min-w-48">
                            <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                            <select name="tahun" id="tahun"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Tahun</option>
                                @foreach($tahuns as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun')==$tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="min-w-48">
                            <label for="triwulan" class="block text-sm font-medium text-gray-700">Triwulan</label>
                            <select name="triwulan" id="triwulan"
                                class="w-full px-3 mt-1 border border-gray-300 rounded-md h-9 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Triwulan</option>
                                <option value="1" {{ request('triwulan')=='1' ? 'selected' : '' }}>Triwulan 1</option>
                                <option value="2" {{ request('triwulan')=='2' ? 'selected' : '' }}>Triwulan 2</option>
                                <option value="3" {{ request('triwulan')=='3' ? 'selected' : '' }}>Triwulan 3</option>
                                <option value="4" {{ request('triwulan')=='4' ? 'selected' : '' }}>Triwulan 4</option>
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 text-gray-700 bg-white border border-gray-300 rounded-md h-9 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                            @if(request('search') || request('tahun') || request('triwulan'))
                            <a href="{{ route('admin.triwulan.index') }}"
                                class="inline-flex items-center px-4 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md h-9 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reset
                            </a>
                            @endif

                            <!-- Sinkronkan & Export Buttons -->
                            <div class="flex space-x-2">
                                <button onclick="syncAllData()"
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

                                <a href="{{ route('admin.triwulan.export.excel', request()->only(['search','tahun','triwulan'])) }}"
                                    class="inline-flex items-center px-4 py-2 text-sm font-semibold tracking-widest transition duration-150 ease-in-out bg-white border rounded-md shadow-sm border-emerald-500 text-emerald-600 hover:bg-emerald-50 focus:bg-emerald-50 active:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 hover:shadow"
                                    title="Export Excel (XLSX)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-emerald-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Ekspor Data
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="w-full overflow-x-auto bg-white rounded-lg shadow-md">
                    <table class="w-full border-collapse table-fixed min-w-max">
                        <thead>
                            <tr class="bg-gray-100">
                                <th
                                    class="w-12 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    No</th>
                                <th
                                    class="w-36 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    Periode</th>
                                <th
                                    class="w-56 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    Nama Barang</th>
                                <th
                                    class="w-24 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    Satuan</th>
                                <th
                                    class="w-24 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    Harga Satuan</th>
                                <th
                                    class="w-16 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    Saldo Awal</th>
                                <th
                                    class="w-16 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    Total Kredit</th>
                                <!-- Hidden: Total Harga Kredit -->
                                <th
                                    class="w-16 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    Total Debit</th>
                                <th
                                    class="w-32 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    Total Harga Debit</th>
                                <th
                                    class="w-32 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    Total Persediaan</th>
                                <th
                                    class="w-36 px-3 py-3 text-xs font-semibold tracking-wider text-center text-gray-900 uppercase border border-gray-300">
                                    Total Harga Persediaan</th>
                                <!-- Hidden: Aksi column -->
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($triwulans as $index => $triwulan)
                            <tr class="transition-colors duration-200 ease-in-out hover:bg-gray-50">
                                <td
                                    class="px-3 py-4 text-sm font-medium text-gray-900 border border-gray-300 whitespace-nowrap text-center align-top">
                                    {{ ($triwulans->currentPage() - 1) * $triwulans->perPage() + $index + 1 }}
                                </td>
                                <td class="px-3 py-4 border border-gray-300 align-top">
                                    <div class="text-sm font-medium text-gray-900 break-words leading-relaxed">{{
                                        $triwulan->nama_triwulan }}</div>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 align-top">
                                    <div class="text-sm font-medium text-gray-900 break-words leading-relaxed">{{
                                        $triwulan->nama_barang }}</div>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap align-top">
                                    <span class="text-sm text-gray-900">{{ $triwulan->satuan }}</span>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap text-right align-top">
                                    <span class="text-sm text-gray-900">Rp {{ number_format($triwulan->harga_satuan, 0,
                                        ',', '.') }}</span>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap text-right align-top">
                                    <span class="text-sm font-medium text-gray-900">{{
                                        number_format($triwulan->saldo_awal_triwulan, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap text-right align-top">
                                    <span class="text-sm font-medium text-red-600">{{
                                        number_format($triwulan->total_kredit_triwulan, 0, ',', '.') }}</span>
                                </td>
                                <!-- Hidden: Total Harga Kredit cell -->
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap text-right align-top">
                                    <span class="text-sm font-medium text-green-600">{{
                                        number_format($triwulan->total_debit_triwulan, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-2 py-4 border border-gray-300 text-right align-top">
                                    <span class="text-xs text-green-600 break-words leading-tight">Rp {{
                                        number_format($triwulan->total_harga_debit, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-3 py-4 border border-gray-300 whitespace-nowrap text-right align-top">
                                    <span class="text-sm font-medium text-blue-600">{{
                                        number_format($triwulan->total_persediaan_triwulan, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-2 py-4 border border-gray-300 text-right align-top">
                                    <span class="text-xs font-medium text-blue-600 break-words leading-tight">Rp {{
                                        number_format($triwulan->total_harga_persediaan, 0, ',', '.') }}</span>
                                </td>
                                <!-- Hidden: Aksi column -->
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-calendar-alt text-4xl text-gray-400 mb-2"></i>
                                        <p class="text-base font-medium text-gray-500">Belum ada data triwulan</p>
                                        <p class="text-sm text-gray-400 mt-1">Gunakan tombol "Generate Data" untuk
                                            membuat laporan triwulan</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($triwulans->hasPages())
                <div class="mt-4">
                    {{ $triwulans->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for sync all data -->
<form id="syncAllForm" action="{{ route('admin.triwulan.syncall') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    function syncAllData() {
    // Show loading directly without confirmation
    Swal.fire({
        title: 'Menyinkronkan Data...',
        text: 'Sedang memproses sinkronisasi data triwulan',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Submit form directly
    document.getElementById('syncAllForm').submit();
}function deleteTriwulan(id) {
    Swal.fire({
        title: 'Hapus Data Triwulan?',
        text: 'Yakin ingin menghapus data triwulan ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="mr-2 fas fa-trash"></i>Hapus!',
        cancelButtonText: '<i class="mr-2 fas fa-times"></i>Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                text: 'Sedang menghapus data triwulan',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send delete request
            fetch(`/admin/triwulan/${id}`, {
                method: 'DELETE',
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
                        text: data.message || 'Data triwulan berhasil dihapus',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan saat menghapus data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.message,
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: '<i class="mr-2 fas fa-times"></i>OK'
                });
            });
        }
    });
}

// Function to update statistics in real-time
function updateStatistics() {
    const form = document.querySelector('form[action="{{ route('admin.triwulan.index') }}"]');
    if (!form) return;

    const formData = new FormData(form);
    const params = new URLSearchParams();

    // Convert FormData to URLSearchParams
    for (let [key, value] of formData.entries()) {
        if (value && value.trim() !== '') {
            params.append(key, value);
        }
    }

    // Show loading indicators
    showLoadingState();

    // Fetch updated statistics
    fetch('{{ route('admin.triwulan.statistics') }}?' + params.toString(), {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.data) {
            // Update statistics cards
            updateStatCard('total-kredit', data.data.total_kredit);
            updateStatCard('total-debit', data.data.total_debit);
            updateStatCard('total-persediaan', data.data.total_persediaan);
            updateStatCard('total-harga-debit', data.data.total_harga_debit, true);
            updateStatCard('total-harga-persediaan', data.data.total_harga_persediaan, true);

            // Add success animation
            addUpdateAnimation();
        } else {
            throw new Error(data.message || 'Invalid data received');
        }
    })
    .catch(error => {
        console.error('Error updating statistics:', error);
        showErrorState(error.message);
    })
    .finally(() => {
        hideLoadingState();
    });
}

// Helper function to update individual stat card
function updateStatCard(elementId, value, isRupiah = false) {
    const element = document.getElementById(elementId);
    if (element) {
        const formattedValue = isRupiah ?
            'Rp ' + numberFormat(value || 0) :
            numberFormat(value || 0);
        element.textContent = formattedValue;
    }
}

// Helper function to show loading state
function showLoadingState() {
    const statCards = ['total-kredit', 'total-debit', 'total-persediaan', 'total-harga-debit', 'total-harga-persediaan'];
    statCards.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.classList.add('opacity-50');
        }
    });
}

// Helper function to hide loading state
function hideLoadingState() {
    const statCards = ['total-kredit', 'total-debit', 'total-persediaan', 'total-harga-debit', 'total-harga-persediaan'];
    statCards.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.classList.remove('opacity-50');
        }
    });
}

// Helper function to add update animation
function addUpdateAnimation() {
    const statCards = ['total-kredit', 'total-debit', 'total-persediaan', 'total-harga-debit', 'total-harga-persediaan'];
    statCards.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.classList.add('animate-pulse');
            setTimeout(() => {
                element.classList.remove('animate-pulse');
            }, 1000);
        }
    });
}

// Helper function to show error state
function showErrorState(message) {
    console.warn('Statistics update failed:', message);
}

// Number formatting function
function numberFormat(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Event listeners for real-time updates
document.addEventListener('DOMContentLoaded', function() {
    // Listen for changes in filter inputs
    const filterInputs = document.querySelectorAll('#search, #tahun, #triwulan');

    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Small delay to allow user to finish typing/selecting
            setTimeout(updateStatistics, 300);
        });
    });

    // For search input, also listen to input event (typing)
    const searchInput = document.querySelector('#search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(updateStatistics, 800); // Longer delay for typing
        });
    }

    // Listen for form submission to update statistics immediately
    const filterForm = document.querySelector('form[action="{{ route('admin.triwulan.index') }}"]');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            // Don't prevent default, but update statistics
            setTimeout(updateStatistics, 100);
        });
    }
});
</script>

@endsection