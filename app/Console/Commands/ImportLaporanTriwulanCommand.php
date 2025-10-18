<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LaporanTriwulan;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportLaporanTriwulanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laporan:import-csv {file} {--year=} {--triwulan=} {--delimiter=,} {--skip-header=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import laporan triwulan dari file CSV. Format: barang_id,nama_barang,satuan,saldo_awal,pengadaan,harga_satuan,pemakaian,saldo_akhir';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        $year = $this->option('year') ?: Carbon::now()->year;
        $triwulan = $this->option('triwulan') ?: $this->getCurrentQuarter();
        $delimiter = $this->option('delimiter') ?: ',';
        $skipHeader = (int) $this->option('skip-header');

        // Validasi file
        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        if (!is_readable($filePath)) {
            $this->error("File tidak dapat dibaca: {$filePath}");
            return 1;
        }

        // Validasi input
        if ($triwulan < 1 || $triwulan > 4) {
            $this->error('Triwulan harus bernilai 1 sampai 4');
            return 1;
        }

        $this->info("Importing CSV file: {$filePath}");
        $this->info("Target: Tahun {$year}, Triwulan {$triwulan}");
        $this->line("Delimiter: '{$delimiter}', Skip header: {$skipHeader} rows");

        return $this->processCSV($filePath, $year, $triwulan, $delimiter, $skipHeader);
    }

    /**
     * Process CSV file and import data
     */
    private function processCSV($filePath, $year, $triwulan, $delimiter, $skipHeader)
    {
        try {
            DB::beginTransaction();

            $handle = fopen($filePath, 'r');
            if (!$handle) {
                throw new \Exception("Tidak dapat membuka file CSV");
            }

            $rowNumber = 0;
            $imported = 0;
            $errors = [];
            $skipped = 0;

            // Skip header rows
            for ($i = 0; $i < $skipHeader; $i++) {
                if (feof($handle)) break;
                fgets($handle);
                $rowNumber++;
            }

            $this->output->progressStart();

            while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $rowNumber++;
                $this->output->progressAdvance();

                // Skip empty rows
                if (empty(array_filter($data))) {
                    $skipped++;
                    continue;
                }

                $result = $this->processRow($data, $year, $triwulan, $rowNumber);

                if ($result['success']) {
                    $imported++;
                } else {
                    $errors[] = "Row {$rowNumber}: " . $result['error'];
                }
            }

            $this->output->progressFinish();
            fclose($handle);

            // Show results
            $this->newLine();
            $this->info("Import completed!");
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Total Rows Processed', $rowNumber - $skipHeader],
                    ['Successfully Imported', $imported],
                    ['Errors', count($errors)],
                    ['Skipped (Empty)', $skipped]
                ]
            );

            // Show errors if any
            if (!empty($errors)) {
                $this->newLine();
                $this->error("Errors encountered:");
                foreach (array_slice($errors, 0, 10) as $error) { // Show max 10 errors
                    $this->line("  - {$error}");
                }
                if (count($errors) > 10) {
                    $this->line("  ... and " . (count($errors) - 10) . " more errors");
                }
            }

            DB::commit();
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Import failed: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Process single CSV row
     */
    private function processRow($data, $year, $triwulan, $rowNumber)
    {
        try {
            // Expected CSV format:
            // barang_id, nama_barang, satuan, saldo_akhir_sebelumnya, jumlah_pengadaan, harga_satuan, jumlah_pemakaian, saldo_tersedia

            if (count($data) < 8) {
                return ['success' => false, 'error' => 'Insufficient columns (expected 8)'];
            }

            $rowData = [
                'barang_id' => trim($data[0]),
                'nama_barang' => trim($data[1]),
                'satuan' => trim($data[2]),
                'saldo_akhir_sebelumnya' => (int) $data[3],
                'jumlah_pengadaan' => (int) $data[4],
                'harga_satuan' => (float) $data[5],
                'jumlah_pemakaian' => (int) $data[6],
                'saldo_tersedia' => (int) $data[7]
            ];

            // Validate data
            $validator = Validator::make($rowData, [
                'barang_id' => 'required|string|max:255',
                'nama_barang' => 'required|string|max:255',
                'satuan' => 'required|string|max:50',
                'saldo_akhir_sebelumnya' => 'integer|min:0',
                'jumlah_pengadaan' => 'integer|min:0',
                'harga_satuan' => 'numeric|min:0',
                'jumlah_pemakaian' => 'integer|min:0',
                'saldo_tersedia' => 'integer|min:0'
            ]);

            if ($validator->fails()) {
                return ['success' => false, 'error' => 'Validation failed: ' . implode(', ', $validator->errors()->all())];
            }

            // Calculate derived fields
            $rowData['tahun'] = $year;
            $rowData['triwulan'] = $triwulan;
            $rowData['jumlah_harga'] = $rowData['harga_satuan'] * $rowData['saldo_akhir_sebelumnya'];
            $rowData['total_harga'] = $rowData['harga_satuan'] * $rowData['saldo_tersedia'];

            // Check if barang exists
            $barang = Barang::where('id_barang', $rowData['barang_id'])->first();
            if (!$barang) {
                return ['success' => false, 'error' => "Barang ID {$rowData['barang_id']} tidak ditemukan"];
            }

            // Check for duplicate
            $existing = LaporanTriwulan::where('barang_id', $rowData['barang_id'])
                ->where('tahun', $year)
                ->where('triwulan', $triwulan)
                ->first();

            if ($existing) {
                // Update existing record
                $existing->update($rowData);
            } else {
                // Create new record
                LaporanTriwulan::create($rowData);
            }

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get current quarter
     */
    private function getCurrentQuarter()
    {
        $month = Carbon::now()->month;

        if ($month >= 1 && $month <= 3) return 1;
        if ($month >= 4 && $month <= 6) return 2;
        if ($month >= 7 && $month <= 9) return 3;
        if ($month >= 10 && $month <= 12) return 4;

        return 1;
    }

    /**
     * Show usage help
     */
    public function showHelp()
    {
        $this->info('CSV Import Command for Laporan Triwulan');
        $this->newLine();

        $this->info('Usage:');
        $this->line('  php artisan laporan:import-csv {file} [options]');
        $this->newLine();

        $this->info('Expected CSV Format:');
        $this->line('  barang_id,nama_barang,satuan,saldo_akhir_sebelumnya,jumlah_pengadaan,harga_satuan,jumlah_pemakaian,saldo_tersedia');
        $this->newLine();

        $this->info('Options:');
        $this->line('  --year=2025           Target year (default: current year)');
        $this->line('  --triwulan=4          Target quarter 1-4 (default: current quarter)');
        $this->line('  --delimiter=,         CSV delimiter (default: comma)');
        $this->line('  --skip-header=1       Number of header rows to skip (default: 1)');
        $this->newLine();

        $this->info('Examples:');
        $this->line('  php artisan laporan:import-csv /path/to/data.csv');
        $this->line('  php artisan laporan:import-csv data.csv --year=2025 --triwulan=4');
        $this->line('  php artisan laporan:import-csv data.csv --delimiter=";" --skip-header=2');
    }
}
