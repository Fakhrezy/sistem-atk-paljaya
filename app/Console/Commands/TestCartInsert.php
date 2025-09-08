<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TestCartInsert extends Command
{
    protected $signature = 'test:cart';
    protected $description = 'Test cart insertion';

    public function handle()
    {
        $this->info('Testing cart insertion...');

        // Get test data
        $user = User::where('role', 'user')->first();
        $barang = Barang::first();

        if (!$user) {
            $this->error('No user found with role=user');
            return;
        }

        if (!$barang) {
            $this->error('No barang found');
            return;
        }

        $this->info("Using user: {$user->id} - {$user->name}");
        $this->info("Using barang: {$barang->id_barang} - {$barang->nama_barang}");

        try {
            // Test direct DB insert
            $cartId = DB::table('cart')->insertGetId([
                'user_id' => $user->id,
                'id_barang' => $barang->id_barang,
                'quantity' => 1,
                'bidang' => 'umum',
                'keterangan' => 'test insert',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info("✅ Direct DB insert successful! Cart ID: {$cartId}");

            // Test Eloquent create
            $cart = Cart::create([
                'user_id' => $user->id,
                'id_barang' => $barang->id_barang,
                'quantity' => 2,
                'bidang' => 'perencanaan',
                'keterangan' => 'test eloquent',
            ]);

            $this->info("✅ Eloquent create successful! Cart ID: {$cart->id}");

            // Test relationship
            $cart->load('barang', 'user');
            $this->info("✅ Relationships work:");
            $this->info("   - Barang: {$cart->barang->nama_barang}");
            $this->info("   - User: {$cart->user->name}");

            // Clean up
            Cart::where('user_id', $user->id)->delete();
            $this->info("✅ Cleanup completed");

        } catch (\Exception $e) {
            $this->error("❌ Error: {$e->getMessage()}");
            $this->error("Stack trace: {$e->getTraceAsString()}");
        }
    }
}
