<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetailMonitoringBarang;
use App\Models\Barang;
use Carbon\Carbon;

class TestSaldoAwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil barang pertama untuk testing
        $barang = Barang::first();

        if (!$barang) {
            $this->command->error('Tidak ada data barang untuk testing. Jalankan BarangSeeder terlebih dahulu.');
            return;
        }

        $this->command->info("Testing saldo awal triwulan dengan barang: {$barang->nama_barang}");

        // Hapus data lama untuk barang ini
        DetailMonitoringBarang::where('id_barang', $barang->id_barang)->delete();

        // Scenario: Data saldo awal di akhir triwulan sebelumnya (Juni 2025)
        DetailMonitoringBarang::create([
            'nama_barang' => $barang->nama_barang,
            'id_barang' => $barang->id_barang,
            'tanggal' => '2025-06-30', // Akhir triwulan 2
            'saldo' => 150, // Saldo akhir triwulan 2 = saldo awal triwulan 3
            'debit' => null,
            'kredit' => null,
            'keterangan' => 'Saldo akhir triwulan 2 - AKAN JADI SALDO AWAL TRIWULAN 3',
            'bidang' => null,
            'pengambil' => null,
            'monitoring_barang_id' => null,
            'monitoring_pengadaan_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Data dalam triwulan 3 (Juli-September 2025)
        $dataTriwulan3 = [
            [
                'tanggal' => '2025-07-05',
                'saldo' => 200, // 150 + 50
                'debit' => 50, // Pengadaan
                'kredit' => null,
                'keterangan' => 'Pengadaan awal Juli - Test Saldo Awal'
            ],
            [
                'tanggal' => '2025-07-20',
                'saldo' => 180, // 200 - 20
                'debit' => null,
                'kredit' => 20, // Pengambilan
                'keterangan' => 'Pengambilan pertama Juli - Test Saldo Awal'
            ],
            [
                'tanggal' => '2025-08-15',
                'saldo' => 155, // 180 - 25
                'debit' => null,
                'kredit' => 25, // Pengambilan
                'keterangan' => 'Pengambilan Agustus - Test Saldo Awal'
            ]
        ];

        foreach ($dataTriwulan3 as $data) {
            DetailMonitoringBarang::create(array_merge($data, [
                'nama_barang' => $barang->nama_barang,
                'id_barang' => $barang->id_barang,
                'bidang' => 'Sekretariat',
                'pengambil' => 'Test User',
                'monitoring_barang_id' => null,
                'monitoring_pengadaan_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }

        $this->command->info('✅ Test data saldo awal berhasil dibuat!');
        $this->command->info('📊 Expected Results untuk Triwulan 3 (Juli-Sept 2025):');
        $this->command->info('   • Saldo Awal: 150 (dari 30 Juni 2025)');
        $this->command->info('   • Total Debit: 50 (pengadaan 5 Juli)');
        $this->command->info('   • Total Kredit: 45 (20 + 25, pengambilan Juli & Agustus)');
        $this->command->info('   • Total Persediaan: 150 + 50 - 45 = 155');
        $this->command->info('🔄 Observer akan auto-sync data triwulan');
        $this->command->info('🖱️ Atau gunakan tombol "Sinkronkan Data" di halaman web');
    }
}
