<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetailMonitoringBarang;
use App\Models\Barang;
use Carbon\Carbon;

class TestAutoSyncSeeder extends Seeder
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

        $this->command->info("Testing auto-sync dengan barang: {$barang->nama_barang}");

        // Data test baru untuk triwulan 4 (Oktober 2025)
        $newData = [
            [
                'nama_barang' => $barang->nama_barang,
                'id_barang' => $barang->id_barang,
                'tanggal' => '2025-10-01',
                'saldo' => 200, // Saldo awal Oktober
                'debit' => 100, // Pengadaan
                'kredit' => null,
                'keterangan' => 'Pengadaan bulanan Oktober - TEST AUTO SYNC',
                'bidang' => null,
                'pengambil' => null,
                'monitoring_barang_id' => null,
                'monitoring_pengadaan_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => $barang->nama_barang,
                'id_barang' => $barang->id_barang,
                'tanggal' => '2025-10-15',
                'saldo' => 275, // 200 + 100 - 25
                'debit' => null,
                'kredit' => 25, // Pengambilan
                'keterangan' => 'Pengambilan untuk kegiatan Oktober - TEST AUTO SYNC',
                'bidang' => 'Sekretariat',
                'pengambil' => 'Test User',
                'monitoring_barang_id' => null,
                'monitoring_pengadaan_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($newData as $data) {
            $this->command->info("Menambahkan data: {$data['tanggal']} - {$data['keterangan']}");

            // Ini akan trigger observer dan auto-sync ke tabel triwulan
            DetailMonitoringBarang::create($data);
        }

        $this->command->info('✅ Test data berhasil ditambahkan!');
        $this->command->info('🔄 Data triwulan akan otomatis ter-update melalui Observer');
        $this->command->info('📅 Periode: Triwulan 4 (Oktober-Desember) 2025');
        $this->command->info('📊 Cek halaman Data Triwulan untuk melihat hasil auto-sync');
    }
}
