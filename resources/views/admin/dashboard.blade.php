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
                    <h2 class="text-2xl font-semibold text-gray-800">Statistik Barang</h2>

                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Total Barang -->
                    <div class="overflow-hidden rounded-lg shadow bg-gradient-to-r from-blue-500 to-blue-600">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 w-0 ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-blue-100 truncate">Total Barang</dt>
                                        <dd class="text-lg font-medium text-white">{{ $stats['total'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ATK -->
                    <div class="overflow-hidden rounded-lg shadow bg-gradient-to-r from-green-500 to-green-600">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 w-0 ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-green-100 truncate">ATK</dt>
                                        <dd class="text-lg font-medium text-white">{{ $stats['atk'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cetak -->
                    <div class="overflow-hidden rounded-lg shadow bg-gradient-to-r from-yellow-500 to-yellow-600">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 w-0 ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-yellow-100 truncate">Cetakan</dt>
                                        <dd class="text-lg font-medium text-white">{{ $stats['cetak'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tinta -->
                    <div class="overflow-hidden rounded-lg shadow bg-gradient-to-r from-purple-500 to-purple-600">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 14.5v3.5c0 1.657-3.134 3-7 3s-7-1.343-7-3v-3.5m14 0c0 1.657-3.134 3-7 3s-7-1.343-7-3m14 0v-2c0-1.657-3.134-3-7-3s-7 1.343-7 3v2m14 0c0 1.657-3.134 3-7 3s-7-1.343-7-3">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 w-0 ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-purple-100 truncate">Tinta</dt>
                                        <dd class="text-lg font-medium text-white">{{ $stats['tinta'] }}</dd>
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
                                <h3 class="text-xl font-semibold text-gray-800">Barang dengan Stok Paling Sedikit</h3>
                                <p class="text-sm text-gray-600 mt-1">10 barang dengan stok terendah yang perlu
                                    diperhatikan</p>

                                <!-- Legend -->
                                <div class="flex flex-wrap gap-4 mt-3">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                                        <span class="text-sm text-gray-600">ATK</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                        <span class="text-sm text-gray-600">Cetakan</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                                        <span class="text-sm text-gray-600">Tinta</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Chart Container -->
                            <div class="w-full">
                                @if($lowStockItems->count() > 0)
                                <canvas id="lowStockChart" width="400" height="200"></canvas>
                                @else
                                <div class="flex items-center justify-center h-64 bg-gray-50 rounded-lg">
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
                return item.nama_barang.length > 15
                    ? item.nama_barang.substring(0, 15) + '...'
                    : item.nama_barang;
            });
            const stockData = chartData.map(item => item.stok);

            // Color mapping for different categories
            const getBarColor = (jenis) => {
                switch(jenis) {
                    case 'atk': return 'rgba(251, 191, 36, 0.8)'; // Yellow
                    case 'cetak': return 'rgba(34, 197, 94, 0.8)'; // Green
                    case 'tinta': return 'rgba(59, 130, 246, 0.8)'; // Blue
                    default: return 'rgba(156, 163, 175, 0.8)'; // Gray for unknown
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
                                text: 'Jumlah Stok'
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
                                text: 'Nama Barang'
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: {
                                    size: 11
                                }
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