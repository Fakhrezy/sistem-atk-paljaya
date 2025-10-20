<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DetailMonitoringBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data barang yang sudah ada
        $pulpenBiru = \App\Models\Barang::where('nama_barang', 'like', '%Pulpen Biru%')->first();
        $kertasA4 = \App\Models\Barang::where('nama_barang', 'like', '%Kertas A4%')->first();
        $spidol = \App\Models\Barang::where('nama_barang', 'like', '%Spidol%')->first();
        $mapPlastik = \App\Models\Barang::where('nama_barang', 'like', '%Map Plastik%')->first();
        $stapler = \App\Models\Barang::where('nama_barang', 'like', '%Stapler%')->first();

        // Data sample untuk triwulan 3 (Juli - September 2025)
        $data = [
            // Pulpen Biru Standard
            [
                'nama_barang' => $pulpenBiru->nama_barang,
                'id_barang' => $pulpenBiru->id_barang,
                'tanggal' => '2025-07-01',
                'saldo' => 100, // Saldo awal Juli
                'debit' => 50, // Pengadaan
                'kredit' => null,
                'keterangan' => 'Pengadaan bulanan',
                'bidang' => null,
                'pengambil' => null,
                'monitoring_barang_id' => null,
                'monitoring_pengadaan_id' => null,
            ],
            [
                'nama_barang' => $pulpenBiru->nama_barang,
                'id_barang' => $pulpenBiru->id_barang,
                'tanggal' => '2025-07-15',
                'saldo' => 130, // 100 + 50 - 20
                'debit' => null,
                'kredit' => 20, // Pengambilan
                'keterangan' => 'Pengambilan untuk kegiatan rapat',
                'bidang' => 'Sekretariat',
                'pengambil' => 'Ahmad Fauzi',
                'monitoring_barang_id' => null,
                'monitoring_pengadaan_id' => null,
            ],
            [
                'nama_barang' => $pulpenBiru->nama_barang,
                'id_barang' => $pulpenBiru->id_barang,
                'tanggal' => '2025-08-05',
                'saldo' => 115, // 130 - 15
                'debit' => null,
                'kredit' => 15,
                'keterangan' => 'Pengambilan untuk kegiatan pelatihan',
                'bidang' => 'Keuangan',
                'pengambil' => 'Siti Nurhaliza',
                'monitoring_barang_id' => 2,
                'monitoring_pengadaan_id' => null,
            ],
            [
                'nama_barang' => $pulpenBiru->nama_barang,
                'id_barang' => $pulpenBiru->id_barang,
                'tanggal' => '2025-09-10',
                'saldo' => 105, // 115 - 10
                'debit' => null,
                'kredit' => 10,
                'keterangan' => 'Pengambilan untuk kegiatan seminar',
                'bidang' => 'Administrasi',
                'pengambil' => 'Budi Santoso',
                'monitoring_barang_id' => 3,
                'monitoring_pengadaan_id' => null,
            ],

            // Kertas A4 80gsm
            [
                'nama_barang' => $kertasA4->nama_barang,
                'id_barang' => $kertasA4->id_barang,
                'tanggal' => '2025-07-01',
                'saldo' => 500, // Saldo awal Juli
                'debit' => 200, // Pengadaan
                'kredit' => null,
                'keterangan' => 'Pengadaan bulanan',
                'bidang' => null,
                'pengambil' => null,
                'monitoring_barang_id' => null,
                'monitoring_pengadaan_id' => 2,
            ],
            [
                'nama_barang' => $kertasA4->nama_barang,
                'id_barang' => $kertasA4->id_barang,
                'tanggal' => '2025-07-20',
                'saldo' => 650, // 500 + 200 - 50
                'debit' => null,
                'kredit' => 50,
                'keterangan' => 'Pengambilan untuk laporan bulanan',
                'bidang' => 'Sekretariat',
                'pengambil' => 'Ahmad Fauzi',
                'monitoring_barang_id' => 4,
                'monitoring_pengadaan_id' => null,
            ],
            [
                'nama_barang' => $kertasA4->nama_barang,
                'id_barang' => $kertasA4->id_barang,
                'tanggal' => '2025-08-15',
                'saldo' => 575, // 650 - 75
                'debit' => null,
                'kredit' => 75,
                'keterangan' => 'Pengambilan untuk dokumentasi',
                'bidang' => 'Keuangan',
                'pengambil' => 'Siti Nurhaliza',
                'monitoring_barang_id' => 5,
                'monitoring_pengadaan_id' => null,
            ],
            [
                'nama_barang' => $kertasA4->nama_barang,
                'id_barang' => $kertasA4->id_barang,
                'tanggal' => '2025-09-25',
                'saldo' => 525, // 575 - 50
                'debit' => null,
                'kredit' => 50,
                'keterangan' => 'Pengambilan untuk arsip',
                'bidang' => 'Administrasi',
                'pengambil' => 'Budi Santoso',
                'monitoring_barang_id' => 6,
                'monitoring_pengadaan_id' => null,
            ],

            // Spidol Whiteboard Snowman
            [
                'nama_barang' => $spidol->nama_barang,
                'id_barang' => $spidol->id_barang,
                'tanggal' => '2025-07-01',
                'saldo' => 25, // Saldo awal Juli
                'debit' => 15, // Pengadaan
                'kredit' => null,
                'keterangan' => 'Pengadaan bulanan',
                'bidang' => null,
                'pengambil' => null,
                'monitoring_barang_id' => null,
                'monitoring_pengadaan_id' => 3,
            ],
            [
                'nama_barang' => $spidol->nama_barang,
                'id_barang' => $spidol->id_barang,
                'tanggal' => '2025-07-25',
                'saldo' => 35, // 25 + 15 - 5
                'debit' => null,
                'kredit' => 5,
                'keterangan' => 'Pengambilan untuk ruang rapat',
                'bidang' => 'Sekretariat',
                'pengambil' => 'Ahmad Fauzi',
                'monitoring_barang_id' => 7,
                'monitoring_pengadaan_id' => null,
            ],
            [
                'nama_barang' => $spidol->nama_barang,
                'id_barang' => $spidol->id_barang,
                'tanggal' => '2025-08-20',
                'saldo' => 30, // 35 - 5
                'debit' => null,
                'kredit' => 5,
                'keterangan' => 'Pengambilan untuk presentasi',
                'bidang' => 'Keuangan',
                'pengambil' => 'Siti Nurhaliza',
                'monitoring_barang_id' => 8,
                'monitoring_pengadaan_id' => null,
            ],

            // Map Plastik Bening
            [
                'nama_barang' => $mapPlastik->nama_barang,
                'id_barang' => $mapPlastik->id_barang,
                'tanggal' => '2025-07-01',
                'saldo' => 80, // Saldo awal Juli
                'debit' => 30, // Pengadaan
                'kredit' => null,
                'keterangan' => 'Pengadaan bulanan',
                'bidang' => null,
                'pengambil' => null,
                'monitoring_barang_id' => null,
                'monitoring_pengadaan_id' => 4,
            ],
            [
                'nama_barang' => $mapPlastik->nama_barang,
                'id_barang' => $mapPlastik->id_barang,
                'tanggal' => '2025-07-30',
                'saldo' => 95, // 80 + 30 - 15
                'debit' => null,
                'kredit' => 15,
                'keterangan' => 'Pengambilan untuk arsip dokumen',
                'bidang' => 'Administrasi',
                'pengambil' => 'Budi Santoso',
                'monitoring_barang_id' => 9,
                'monitoring_pengadaan_id' => null,
            ],
            [
                'nama_barang' => $mapPlastik->nama_barang,
                'id_barang' => $mapPlastik->id_barang,
                'tanggal' => '2025-08-25',
                'saldo' => 85, // 95 - 10
                'debit' => null,
                'kredit' => 10,
                'keterangan' => 'Pengambilan untuk organisasi berkas',
                'bidang' => 'Sekretariat',
                'pengambil' => 'Ahmad Fauzi',
                'monitoring_barang_id' => 10,
                'monitoring_pengadaan_id' => null,
            ],
            [
                'nama_barang' => $mapPlastik->nama_barang,
                'id_barang' => $mapPlastik->id_barang,
                'tanggal' => '2025-09-15',
                'saldo' => 75, // 85 - 10
                'debit' => null,
                'kredit' => 10,
                'keterangan' => 'Pengambilan untuk laporan',
                'bidang' => 'Keuangan',
                'pengambil' => 'Siti Nurhaliza',
                'monitoring_barang_id' => 11,
                'monitoring_pengadaan_id' => null,
            ],

            // Stapler Heavy Duty
            [
                'nama_barang' => $stapler->nama_barang,
                'id_barang' => $stapler->id_barang,
                'tanggal' => '2025-07-01',
                'saldo' => 12, // Saldo awal Juli
                'debit' => 8, // Pengadaan
                'kredit' => null,
                'keterangan' => 'Pengadaan bulanan',
                'bidang' => null,
                'pengambil' => null,
                'monitoring_barang_id' => null,
                'monitoring_pengadaan_id' => 5,
            ],
            [
                'nama_barang' => $stapler->nama_barang,
                'id_barang' => $stapler->id_barang,
                'tanggal' => '2025-08-10',
                'saldo' => 18, // 12 + 8 - 2
                'debit' => null,
                'kredit' => 2,
                'keterangan' => 'Pengambilan untuk ruang kerja',
                'bidang' => 'Administrasi',
                'pengambil' => 'Budi Santoso',
                'monitoring_barang_id' => 12,
                'monitoring_pengadaan_id' => null,
            ],
            [
                'nama_barang' => $stapler->nama_barang,
                'id_barang' => $stapler->id_barang,
                'tanggal' => '2025-09-05',
                'saldo' => 16, // 18 - 2
                'debit' => null,
                'kredit' => 2,
                'keterangan' => 'Pengambilan untuk meja kerja',
                'bidang' => 'Keuangan',
                'pengambil' => 'Siti Nurhaliza',
                'monitoring_barang_id' => 13,
                'monitoring_pengadaan_id' => null,
            ]
        ];

        // Insert data ke database
        foreach ($data as $item) {
            // Set semua foreign key menjadi null untuk data sample
            $item['monitoring_barang_id'] = null;
            $item['monitoring_pengadaan_id'] = null;

            DB::table('detail_monitoring_barang')->insert(array_merge($item, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }

        $this->command->info('Data detail monitoring barang untuk triwulan 3 berhasil di-generate!');
        $this->command->info('Total data: ' . count($data) . ' records');
        $this->command->info('Periode: Juli - September 2025');
        $this->command->info('Barang yang di-cover: Pulpen Biru Standard, Kertas A4 80gsm, Spidol Whiteboard Snowman, Map Plastik Bening, Stapler Heavy Duty');
    }
}
