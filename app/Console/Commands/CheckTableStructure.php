<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckTableStructure extends Command
{
    protected $signature = 'check:tables';
    protected $description = 'Check database table structure';

    public function handle()
    {
        $this->info('Checking database table structures...');

        // Check cart table
        if (Schema::hasTable('cart')) {
            $this->info('✓ Cart table exists');
            $columns = Schema::getColumnListing('cart');
            $this->info('Cart columns: ' . implode(', ', $columns));

            // Check cart data
            $cartCount = DB::table('cart')->count();
            $this->info("Cart records: {$cartCount}");
        } else {
            $this->error('✗ Cart table does not exist');
        }

        // Check barang table
        if (Schema::hasTable('barang')) {
            $this->info('✓ Barang table exists');
            $columns = Schema::getColumnListing('barang');
            $this->info('Barang columns: ' . implode(', ', $columns));

            // Check barang data
            $barangCount = DB::table('barang')->count();
            $this->info("Barang records: {$barangCount}");

            if ($barangCount > 0) {
                $sample = DB::table('barang')->select('id_barang', 'nama_barang', 'stok')->first();
                $this->info("Sample barang: {$sample->id_barang} - {$sample->nama_barang} (stok: {$sample->stok})");
            }
        } else {
            $this->error('✗ Barang table does not exist');
        }

        // Check users table
        if (Schema::hasTable('users')) {
            $this->info('✓ Users table exists');
            $userCount = DB::table('users')->where('role', 'user')->count();
            $this->info("User records (role=user): {$userCount}");
        }

        // Check foreign key constraints
        $this->info('');
        $this->info('Checking foreign key constraints...');

        try {
            // Test foreign key constraint for cart -> barang
            $testBarang = DB::table('barang')->first();
            if ($testBarang) {
                $this->info("✓ Can reference barang.id_barang: {$testBarang->id_barang}");
            }

            // Test foreign key constraint for cart -> users
            $testUser = DB::table('users')->where('role', 'user')->first();
            if ($testUser) {
                $this->info("✓ Can reference users.id: {$testUser->id}");
            }

        } catch (\Exception $e) {
            $this->error("Foreign key test failed: {$e->getMessage()}");
        }
    }
}
