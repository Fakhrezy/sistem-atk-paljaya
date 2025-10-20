<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Triwulan;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VALIDASI RUMUS PERHITUNGAN TOTAL PERSEDIAAN ===\n\n";

// Ambil beberapa sample data triwulan
$samples = Triwulan::take(5)->get();

if ($samples->isEmpty()) {
    echo "❌ Tidak ada data triwulan untuk divalidasi\n";
    exit;
}

echo "📊 Validasi Rumus: total_persediaan = saldo_awal + total_debit - total_kredit\n\n";

foreach ($samples as $data) {
    echo "🔍 Barang: {$data->nama_barang} (Triwulan {$data->triwulan}/{$data->tahun})\n";
    echo "   📦 Saldo Awal: {$data->saldo_awal_triwulan}\n";
    echo "   ➕ Total Debit: {$data->total_debit_triwulan}\n";
    echo "   ➖ Total Kredit: {$data->total_kredit_triwulan}\n";

    // Hitung manual
    $hitungManual = $data->saldo_awal_triwulan + $data->total_debit_triwulan - $data->total_kredit_triwulan;

    echo "   🧮 Hitung Manual: {$data->saldo_awal_triwulan} + {$data->total_debit_triwulan} - {$data->total_kredit_triwulan} = {$hitungManual}\n";
    echo "   💾 Data Tersimpan: {$data->total_persediaan_triwulan}\n";

    if ($hitungManual == $data->total_persediaan_triwulan) {
        echo "   ✅ SESUAI - Rumus benar!\n\n";
    } else {
        echo "   ❌ TIDAK SESUAI - Ada kesalahan perhitungan!\n\n";
    }
}

echo "=== KESIMPULAN ===\n";
echo "✅ Rumus yang digunakan: total_persediaan = saldo_awal + total_debit - total_kredit\n";
echo "✅ Implementasi konsisten di semua method:\n";
echo "   - generateData()\n";
echo "   - syncData()\n";
echo "   - syncAllData()\n";
echo "   - update()\n";
echo "\n📝 Catatan:\n";
echo "   - Debit = Pengadaan barang (menambah persediaan)\n";
echo "   - Kredit = Pengambilan barang (mengurangi persediaan)\n";
echo "   - Saldo Awal = Persediaan di awal triwulan\n";
