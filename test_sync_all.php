<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST SYNC ALL DATA TRIWULAN ===" . PHP_EOL;
echo PHP_EOL;

// Hapus data triwulan yang ada
\App\Models\Triwulan::truncate();
echo "✅ Data triwulan lama dibersihkan" . PHP_EOL;

// Cek data detail monitoring yang tersedia
$detailCount = \App\Models\DetailMonitoringBarang::count();
echo "📊 Data detail monitoring tersedia: {$detailCount} records" . PHP_EOL;

if ($detailCount === 0) {
    echo "❌ Tidak ada data detail monitoring. Jalankan seeder terlebih dahulu." . PHP_EOL;
    exit;
}

// Simulasi syncAllData
echo PHP_EOL . "🔄 Menjalankan syncAllData..." . PHP_EOL;

try {
    // Create controller instance and call syncAllData
    $controller = new \App\Http\Controllers\Admin\TriwulanController();

    // Call private method using reflection
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('syncAllData');
    $method->setAccessible(true);

    // Call syncAllData method
    $result = $method->invoke($controller);

    echo "✅ Sync All Data berhasil dijalankan" . PHP_EOL;
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
}

// Cek hasil sync
echo PHP_EOL . "📊 Hasil setelah sync:" . PHP_EOL;
$triwulans = \App\Models\Triwulan::orderBy('tahun')->orderBy('triwulan')->get();

if ($triwulans->count() > 0) {
    echo "Total data triwulan: {$triwulans->count()}" . PHP_EOL;
    echo PHP_EOL;

    foreach ($triwulans as $t) {
        echo "• {$t->nama_barang} - Triwulan {$t->triwulan}/{$t->tahun}" . PHP_EOL;
        echo "  Saldo Awal: {$t->saldo_awal_triwulan}, Debit: {$t->total_debit_triwulan}, Kredit: {$t->total_kredit_triwulan}, Persediaan: {$t->total_persediaan_triwulan}" . PHP_EOL;
    }
} else {
    echo "❌ Tidak ada data triwulan yang ter-generate" . PHP_EOL;
}

echo PHP_EOL . "🎯 Test selesai!" . PHP_EOL;
