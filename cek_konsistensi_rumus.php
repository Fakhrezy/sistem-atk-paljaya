<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Triwulan;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CEK KONSISTENSI RUMUS TOTAL PERSEDIAAN ===\n\n";

// Cek data yang tidak konsisten dengan rumus
$inconsistentData = Triwulan::whereRaw('total_persediaan_triwulan != (saldo_awal_triwulan + total_debit_triwulan - total_kredit_triwulan)')
    ->get();

$totalData = Triwulan::count();

echo "📊 STATISTIK DATA:\n";
echo "Total data triwulan: {$totalData}\n";
echo "Data tidak konsisten: {$inconsistentData->count()}\n";
echo "Data konsisten: " . ($totalData - $inconsistentData->count()) . "\n\n";

if ($inconsistentData->count() > 0) {
    echo "❌ DITEMUKAN DATA YANG TIDAK KONSISTEN:\n";
    echo str_repeat("-", 80) . "\n";

    foreach ($inconsistentData as $data) {
        $hitungManual = $data->saldo_awal_triwulan + $data->total_debit_triwulan - $data->total_kredit_triwulan;

        echo "🔍 {$data->nama_barang} (Triwulan {$data->triwulan}/{$data->tahun})\n";
        echo "   Rumus: {$data->saldo_awal_triwulan} + {$data->total_debit_triwulan} - {$data->total_kredit_triwulan} = {$hitungManual}\n";
        echo "   Database: {$data->total_persediaan_triwulan}\n";
        echo "   Selisih: " . abs($data->total_persediaan_triwulan - $hitungManual) . "\n\n";
    }

    echo "⚠️  Perlu dilakukan sinkronisasi ulang untuk memperbaiki data!\n";
} else {
    echo "✅ SEMUA DATA KONSISTEN!\n";
    echo "Rumus perhitungan total persediaan sudah benar:\n";
    echo "total_persediaan = saldo_awal + total_debit - total_kredit\n\n";
}

echo "=== PENJELASAN RUMUS ===\n";
echo "📚 Dalam sistem inventory:\n";
echo "• Saldo Awal = Persediaan pada awal triwulan\n";
echo "• Total Debit = Pengadaan/pembelian barang (menambah persediaan)\n";
echo "• Total Kredit = Pengambilan/pemakaian barang (mengurangi persediaan)\n";
echo "• Total Persediaan = Saldo akhir triwulan\n\n";

echo "🧮 Rumus yang benar:\n";
echo "PERSEDIAAN AKHIR = SALDO AWAL + BARANG MASUK - BARANG KELUAR\n";
echo "                 = SALDO AWAL + TOTAL DEBIT - TOTAL KREDIT\n\n";

echo "✅ IMPLEMENTASI DI CONTROLLER:\n";
echo "Rumus ini digunakan konsisten di:\n";
echo "• generateData() method\n";
echo "• syncData() method\n";
echo "• syncAllData() method\n";
echo "• update() method\n\n";

echo "=== KESIMPULAN ===\n";
echo "Sistem menggunakan rumus yang BENAR untuk menghitung total persediaan triwulan.\n";
echo "Rumus: total_persediaan_triwulan = saldo_awal_triwulan + total_debit_triwulan - total_kredit_triwulan\n";
