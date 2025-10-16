<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DetailMonitoringBarangService;

class SyncDetailMonitoringCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sync:detail-monitoring';

    /**
     * The console command description.
     */
    protected $description = 'Sinkronisasi data detail monitoring dari monitoring barang dan pengadaan yang sudah disetujui';

    protected $detailMonitoringService;

    /**
     * Create a new command instance.
     */
    public function __construct(DetailMonitoringBarangService $detailMonitoringService)
    {
        parent::__construct();
        $this->detailMonitoringService = $detailMonitoringService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi data detail monitoring...');

        try {
            // Hapus semua data detail monitoring yang ada
            \App\Models\DetailMonitoringBarang::truncate();
            $this->info('Data detail monitoring lama telah dibersihkan.');

            // Sinkronisasi data dengan filter status yang tepat
            $this->detailMonitoringService->syncAllData();

            $this->info('Sinkronisasi berhasil diselesaikan!');
            $this->info('Data monitoring barang dengan status "disetujui", "terima", dan "diterima" telah disinkronkan.');
            $this->info('Data monitoring pengadaan dengan status "selesai" telah disinkronkan.');
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat sinkronisasi: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
