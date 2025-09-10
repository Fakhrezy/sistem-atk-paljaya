<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use App\Models\Barang;

class UpdateCartJenisBarang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:update-jenis-barang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update jenis_barang untuk data cart yang sudah ada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai mengupdate jenis_barang untuk data cart...');

        $cartsWithoutJenis = Cart::whereNull('jenis_barang')->get();

        $this->info("Ditemukan {$cartsWithoutJenis->count()} data cart tanpa jenis_barang");

        $updated = 0;

        foreach ($cartsWithoutJenis as $cart) {
            $this->info("Processing cart ID: {$cart->id} dengan id_barang: {$cart->id_barang}");

            $barang = Barang::find($cart->id_barang);

            if ($barang) {
                $this->info("Barang ditemukan dengan jenis: {$barang->jenis}");
                $cart->update([
                    'jenis_barang' => $barang->jenis
                ]);
                $updated++;
                $this->info("Cart ID {$cart->id} berhasil diupdate");
            } else {
                $this->warn("Barang dengan ID {$cart->id_barang} tidak ditemukan");
            }
        }

        $this->info("Berhasil mengupdate {$updated} data cart");
        $this->info('Selesai!');

        return 0;
    }
}
