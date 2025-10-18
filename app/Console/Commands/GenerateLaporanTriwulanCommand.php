<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LaporanTriwulanService;
use Carbon\Carbon;

class GenerateLaporanTriwulanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laporan:generate-triwulan {--year=} {--triwulan=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate laporan triwulan, optionally specify --year and --triwulan (1-4)';

    protected $service;

    public function __construct(LaporanTriwulanService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->option('year') ?: Carbon::now()->year;
        $triwulan = $this->option('triwulan');

        if ($triwulan) {
            $triwulan = (int) $triwulan;
            if ($triwulan < 1 || $triwulan > 4) {
                $this->error('Triwulan harus bernilai 1 sampai 4');
                return 1;
            }
        } else {
            // If not provided, generate for current triwulan
            $triwulan = $this->service->getTriwulanSaatIni(Carbon::now());
        }

        $this->info("Generating laporan triwulan: Year={$year}, Triwulan={$triwulan} ...");

        $result = $this->service->generateLaporanTriwulan($year, $triwulan);

        if ($result['success']) {
            $this->info($result['message']);
            return 0;
        }

        $this->error($result['message']);
        return 1;
    }
}
