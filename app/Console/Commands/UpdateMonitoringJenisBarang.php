<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MonitoringBarang;
use App\Models\Barang;

class UpdateMonitoringJenisBarang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring:update-jenis-barang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update jenis_barang untuk data monitoring_barang yang sudah ada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai mengupdate jenis_barang untuk data monitoring_barang...');

        $monitoringWithoutJenis = MonitoringBarang::whereNull('jenis_barang')->get();

        $this->info("Ditemukan {$monitoringWithoutJenis->count()} data monitoring tanpa jenis_barang");

        $updated = 0;

        foreach ($monitoringWithoutJenis as $monitoring) {
            $this->info("Processing monitoring ID: {$monitoring->id} untuk barang: {$monitoring->nama_barang}");

            // Cari barang berdasarkan nama_barang
            $barang = Barang::where('nama_barang', $monitoring->nama_barang)->first();

            if ($barang) {
                $this->info("Barang '{$barang->nama_barang}' ditemukan dengan jenis: {$barang->jenis}");
                $monitoring->update([
                    'jenis_barang' => $barang->jenis
                ]);
                $updated++;
                $this->info("Monitoring ID {$monitoring->id} berhasil diupdate");
            } else {
                $this->warn("Barang dengan nama '{$monitoring->nama_barang}' tidak ditemukan");
            }
        }

        $this->info("Berhasil mengupdate {$updated} data monitoring_barang");
        $this->info('Selesai!');

        return 0;
    }
}
