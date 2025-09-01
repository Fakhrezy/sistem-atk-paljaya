<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barang = [
            [
                'nama_barang' => 'Pulpen Biru Standard',
                'satuan' => 'pcs',
                'harga_barang' => 3000,
                'stok' => 50,
                'jenis' => 'atk',
                'foto' => 'pulpen-biru.jpg'
            ],
            [
                'nama_barang' => 'Kertas A4 80gsm',
                'satuan' => 'rim',
                'harga_barang' => 45000,
                'stok' => 25,
                'jenis' => 'cetak',
                'foto' => 'kertas-a4.jpg'
            ],
            [
                'nama_barang' => 'Pensil 2B Faber Castell',
                'satuan' => 'pcs',
                'harga_barang' => 5000,
                'stok' => 30,
                'jenis' => 'atk',
                'foto' => 'pensil-2b.jpg'
            ],
            [
                'nama_barang' => 'Stapler Heavy Duty',
                'satuan' => 'pcs',
                'harga_barang' => 25000,
                'stok' => 8,
                'jenis' => 'atk',
                'foto' => 'stapler.jpg'
            ],
            [
                'nama_barang' => 'Map Plastik Bening',
                'satuan' => 'pcs',
                'harga_barang' => 2500,
                'stok' => 40,
                'jenis' => 'atk',
                'foto' => 'map-plastik.jpg'
            ],
            [
                'nama_barang' => 'Tinta Printer Canon Black',
                'satuan' => 'cartridge',
                'harga_barang' => 85000,
                'stok' => 4,
                'jenis' => 'tinta',
                'foto' => 'tinta-canon.jpg'
            ],
            [
                'nama_barang' => 'Spidol Whiteboard Snowman',
                'satuan' => 'pcs',
                'harga_barang' => 8000,
                'stok' => 15,
                'jenis' => 'atk',
                'foto' => 'spidol-whiteboard.jpg'
            ],
            [
                'nama_barang' => 'Isolasi Bening 2 inch',
                'satuan' => 'roll',
                'harga_barang' => 12000,
                'stok' => 12,
                'jenis' => 'atk',
                'foto' => 'isolasi-bening.jpg'
            ],
            [
                'nama_barang' => 'Amplop Putih Ukuran 90',
                'satuan' => 'pack',
                'harga_barang' => 15000,
                'stok' => 20,
                'jenis' => 'cetak',
                'foto' => 'amplop-putih.jpg'
            ],
            [
                'nama_barang' => 'Correction Pen Tipp-Ex',
                'satuan' => 'pcs',
                'harga_barang' => 7000,
                'stok' => 6,
                'jenis' => 'atk',
                'foto' => 'correction-pen.jpg'
            ],
            [
                'nama_barang' => 'Post-it Notes Kuning',
                'satuan' => 'pack',
                'harga_barang' => 18000,
                'stok' => 18,
                'jenis' => 'atk',
                'foto' => 'post-it.jpg'
            ],
            [
                'nama_barang' => 'Gunting Kenko',
                'satuan' => 'pcs',
                'harga_barang' => 15000,
                'stok' => 10,
                'jenis' => 'atk',
                'foto' => 'gunting.jpg'
            ],
            [
                'nama_barang' => 'Penggaris Plastik 30cm',
                'satuan' => 'pcs',
                'harga_barang' => 4000,
                'stok' => 25,
                'jenis' => 'atk',
                'foto' => 'penggaris.jpg'
            ],
            [
                'nama_barang' => 'Lem Stick UHU',
                'satuan' => 'pcs',
                'harga_barang' => 9000,
                'stok' => 14,
                'jenis' => 'atk',
                'foto' => 'lem-stick.jpg'
            ],
            [
                'nama_barang' => 'Buku Tulis 38 Lembar',
                'satuan' => 'pcs',
                'harga_barang' => 6000,
                'stok' => 35,
                'jenis' => 'cetak',
                'foto' => 'buku-tulis.jpg'
            ],
            [
                'nama_barang' => 'Tinta Printer HP Color',
                'satuan' => 'cartridge',
                'harga_barang' => 95000,
                'stok' => 3,
                'jenis' => 'tinta',
                'foto' => 'tinta-hp-color.jpg'
            ],
            [
                'nama_barang' => 'Kertas Photo Glossy A4',
                'satuan' => 'pack',
                'harga_barang' => 35000,
                'stok' => 8,
                'jenis' => 'cetak',
                'foto' => 'kertas-photo.jpg'
            ],
            [
                'nama_barang' => 'Tinta Refill Epson Black',
                'satuan' => 'botol',
                'harga_barang' => 25000,
                'stok' => 12,
                'jenis' => 'tinta',
                'foto' => 'tinta-refill.jpg'
            ]
        ];

        foreach ($barang as $item) {
            Barang::updateOrCreate(
                ['nama_barang' => $item['nama_barang']],
                $item
            );
        }
    }
}
