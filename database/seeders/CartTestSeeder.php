<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use App\Models\Barang;
use Illuminate\Database\Seeder;

class CartTestSeeder extends Seeder
{
    public function run()
    {
        // Clear existing cart data
        Cart::truncate();

        // Get a user with role 'user'
        $user = User::where('role', 'user')->first();

        if (!$user) {
            $this->command->info('No user found. Creating test user...');
            $user = User::create([
                'name' => 'Test User',
                'email' => 'user@test.com',
                'password' => bcrypt('password'),
                'role' => 'user'
            ]);
        }

        // Get some barang for testing
        $barang = Barang::take(3)->get();

        if ($barang->count() < 3) {
            $this->command->error('Need at least 3 barang in database for testing!');
            return;
        }

        // Create test cart items to demonstrate multiple bidang functionality
        $cartItems = [
            // Same item in different bidang
            [
                'user_id' => $user->id,
                'id_barang' => $barang[0]->id_barang,
                'quantity' => 2,
                'bidang' => 'umum',
                'keterangan' => 'Untuk keperluan bidang umum'
            ],
            [
                'user_id' => $user->id,
                'id_barang' => $barang[0]->id_barang,
                'quantity' => 3,
                'bidang' => 'keuangan',
                'keterangan' => 'Untuk keperluan bidang keuangan'
            ],

            // Different items in same bidang (umum)
            [
                'user_id' => $user->id,
                'id_barang' => $barang[1]->id_barang,
                'quantity' => 1,
                'bidang' => 'umum',
                'keterangan' => 'Item kedua untuk bidang umum'
            ],
            [
                'user_id' => $user->id,
                'id_barang' => $barang[2]->id_barang,
                'quantity' => 4,
                'bidang' => 'umum',
                'keterangan' => 'Item ketiga untuk bidang umum'
            ],

            // Items in other bidang
            [
                'user_id' => $user->id,
                'id_barang' => $barang[1]->id_barang,
                'quantity' => 2,
                'bidang' => 'perencanaan',
                'keterangan' => 'Untuk bidang perencanaan'
            ],
            [
                'user_id' => $user->id,
                'id_barang' => $barang[2]->id_barang,
                'quantity' => 1,
                'bidang' => 'operasional',
                'keterangan' => 'Untuk bidang operasional'
            ]
        ];

        foreach ($cartItems as $item) {
            Cart::create($item);
        }

        $this->command->info('Created ' . count($cartItems) . ' test cart items for user: ' . $user->name);
        $this->command->info('Items distributed across bidang:');

        $cartByBidang = Cart::where('user_id', $user->id)->get()->groupBy('bidang');
        foreach ($cartByBidang as $bidang => $items) {
            $this->command->info("- {$bidang}: {$items->count()} items");
        }
    }
}
