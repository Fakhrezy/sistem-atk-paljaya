<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TriwulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh data triwulan berdasarkan barang yang ada
        $barangs = \App\Models\Barang::limit(5)->get(); // Ambil 5 barang pertama

        foreach ($barangs as $barang) {
            // Buat data untuk triwulan 1 tahun 2025
            \App\Models\Triwulan::create([
                'id_barang' => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'satuan' => $barang->satuan,
                'harga_satuan' => $barang->harga_barang,
                'tahun' => 2025,
                'triwulan' => 1,
                'saldo_awal_triwulan' => rand(50, 200),
                'total_kredit_triwulan' => rand(20, 80),
                'total_harga_kredit' => 0, // Akan dihitung otomatis
                'total_debit_triwulan' => rand(30, 100),
                'total_harga_debit' => 0, // Akan dihitung otomatis
                'total_persediaan_triwulan' => 0, // Akan dihitung otomatis
                'total_harga_persediaan' => 0, // Akan dihitung otomatis
            ]);

            // Buat data untuk triwulan 2 tahun 2025
            \App\Models\Triwulan::create([
                'id_barang' => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'satuan' => $barang->satuan,
                'harga_satuan' => $barang->harga_barang,
                'tahun' => 2025,
                'triwulan' => 2,
                'saldo_awal_triwulan' => rand(40, 180),
                'total_kredit_triwulan' => rand(15, 70),
                'total_harga_kredit' => 0,
                'total_debit_triwulan' => rand(25, 90),
                'total_harga_debit' => 0,
                'total_persediaan_triwulan' => 0,
                'total_harga_persediaan' => 0,
            ]);
        }

        // Update perhitungan otomatis
        $triwulans = \App\Models\Triwulan::all();
        foreach ($triwulans as $triwulan) {
            $triwulan->update([
                'total_harga_kredit' => $triwulan->harga_satuan * $triwulan->total_kredit_triwulan,
                'total_harga_debit' => $triwulan->harga_satuan * $triwulan->total_debit_triwulan,
                'total_persediaan_triwulan' => $triwulan->saldo_awal_triwulan + $triwulan->total_debit_triwulan - $triwulan->total_kredit_triwulan,
                'total_harga_persediaan' => $triwulan->harga_satuan * ($triwulan->saldo_awal_triwulan + $triwulan->total_debit_triwulan - $triwulan->total_kredit_triwulan),
            ]);
        }
    }
}
