@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('header')
SISTEM INFORMASI MONITORING BARANG HABIS PAKAI
@endsection

@section('content')
<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    {{-- <h2 class="text-2xl font-semibold text-gray-800">Statistik Barang</h2> --}}

                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Total Barang -->
                    <div class="overflow-hidden bg-white rounded-lg shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-600 truncate">Total Barang</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ATK -->
                    <div class="overflow-hidden bg-white rounded-lg shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full">
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-600 truncate">ATK</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $stats['atk'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cetak -->
                    <div class="overflow-hidden bg-white rounded-lg shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full">
                                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-600 truncate">Cetakan</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $stats['cetak'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tinta -->
                    <div class="overflow-hidden bg-white rounded-lg shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full">
                                        <svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2.5c-1.5 0-3 1.5-4.5 4.5C6 10 6 12.5 6 14.5c0 3.5 2.7 6.5 6 6.5s6-3 6-6.5c0-2-0-4.5-1.5-7.5C15 4 13.5 2.5 12 2.5zm0 16c-2.2 0-4-1.8-4-4 0-1.3 0-3.2 1.2-5.5C10.2 7 11.1 6 12 6s1.8 1 2.8 3c1.2 2.3 1.2 4.2 1.2 5.5 0 2.2-1.8 4-4 4z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-600 truncate">Tinta</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $stats['tinta'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="mt-8">
                    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="mb-6">
                                <h3 class="text-xl font-semibold text-gray-800">Informasi stok barang terendah
                                </h3>
                                {{-- <p class="mt-1 text-sm text-gray-600">10 barang dengan stok terendah yang
                                    perludiperhatikan</p> --}}

                                <!-- Legend -->
                                {{-- <div class="flex flex-wrap gap-4 mt-3">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 mr-2 bg-green-500 rounded"></div>
                                        <span class="text-sm text-gray-600">ATK</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 mr-2 bg-yellow-500 rounded"></div>
                                        <span class="text-sm text-gray-600">Cetakan</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 mr-2 rounded"
                                            style="background-color: rgba(147, 51, 234, 0.8);"></div>
                                        <span class="text-sm text-gray-600">Tinta</span>
                                    </div>
                                </div> --}}
                            </div>

                            <!-- Chart Container -->
                            <div class="w-full">
                                @if($lowStockItems->count() > 0)
                                <canvas id="lowStockChart" width="400" height="200"></canvas>
                                @else
                                <div class="flex items-center justify-center h-64 rounded-lg bg-gray-50">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">Belum ada data barang</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if($lowStockItems->count() > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const chartElement = document.getElementById('lowStockChart');
            if (!chartElement) return;

            const ctx = chartElement.getContext('2d');

            // Data dari controller
            const chartData = @json($lowStockItems);

            // Prepare data for chart
            const labels = chartData.map(item => {
                // Truncate long names
                return item.nama_barang.length > 20
                    ? item.nama_barang.substring(0, 20) + '...'
                    : item.nama_barang;
            });
            const stockData = chartData.map(item => item.stok);

            // Color mapping for different categories
            const getBarColor = (jenis) => {
                switch(jenis) {
                    case 'atk': return 'rgba(34, 197, 94, 0.8)';
                    case 'cetak': return 'rgba(251, 191, 36, 0.8)';
                    case 'tinta': return 'rgba(147, 51, 234, 0.8)';
                    default: return 'rgba(156, 163, 175, 0.8)';
                }
            };

            const backgroundColors = chartData.map(item => getBarColor(item.jenis));
            const borderColors = chartData.map(item => getBarColor(item.jenis).replace('0.8', '1'));

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Stok',
                        data: stockData,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            callbacks: {
                                title: function(context) {
                                    const index = context[0].dataIndex;
                                    return chartData[index].nama_barang;
                                },
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const jenis = chartData[index].jenis.toUpperCase();
                                    return `Stok: ${context.parsed.y} | Jenis: ${jenis}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah'
                            },
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : '';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Barang'
                            },
                            ticks: {
                                display: false
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20
                        }
                    }
                }
            });

            // Set chart height
            ctx.canvas.style.height = '400px';
        });
</script>
@endif


@endsection
