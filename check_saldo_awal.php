<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== HASIL TEST SALDO AWAL TRIWULAN ===" . PHP_EOL;
echo PHP_EOL;

$triwulans = \App\Models\Triwulan::where('triwulan', 3)
    ->where('tahun', 2025)
    ->get();

if ($triwulans->count() > 0) {
    echo "Data Triwulan 3 - 2025:" . PHP_EOL;
    foreach ($triwulans as $t) {
        echo "• {$t->nama_barang}" . PHP_EOL;
        echo "  - Saldo Awal: {$t->saldo_awal_triwulan}" . PHP_EOL;
        echo "  - Total Debit: {$t->total_debit_triwulan}" . PHP_EOL;
        echo "  - Total Kredit: {$t->total_kredit_triwulan}" . PHP_EOL;
        echo "  - Total Persediaan: {$t->total_persediaan_triwulan}" . PHP_EOL;
        echo PHP_EOL;
    }
} else {
    echo "❌ Tidak ada data triwulan yang ditemukan." . PHP_EOL;
    echo "💡 Jalankan: php artisan tinker" . PHP_EOL;
    echo "   Kemudian: \App\Models\Triwulan::all()" . PHP_EOL;
}

echo "Expected:" . PHP_EOL;
echo "• Saldo Awal: 150 (dari 30 Juni 2025)" . PHP_EOL;
echo "• Total Debit: 50" . PHP_EOL;
echo "• Total Kredit: 45" . PHP_EOL;
echo "• Total Persediaan: 155" . PHP_EOL;
