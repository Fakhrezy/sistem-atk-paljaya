<?php

namespace App\Exports;

use App\Models\Barang;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class BarangExport
{
    protected $search;
    protected $jenis;

    public function __construct($search = null, $jenis = null)
    {
        $this->search = $search;
        $this->jenis = $jenis;
    }

    public function export($filename)
    {
        $writer = new Writer();
        $writer->openToFile($filename);

        // Add header row - sesuai dengan tampilan tabel admin
        $writer->addRow(Row::fromValues([
            'No',
            'Nama Barang',
            'Satuan',
            'Harga',
            'Stok',
            'Jenis'
        ]));

        // Get data with filters if provided
        $query = Barang::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_barang', 'like', "%{$this->search}%")
                    ->orWhere('id_barang', 'like', "%{$this->search}%");
            });
        }

        if ($this->jenis) {
            $query->where('jenis', $this->jenis);
        }

        $barangs = $query->get();

        // Add data rows - dengan nomor urut seperti di tabel admin
        $no = 1;
        foreach ($barangs as $barang) {
            $writer->addRow(Row::fromValues([
                $no++, // Nomor urut
                $barang->nama_barang,
                $barang->satuan,
                'Rp ' . number_format($barang->harga_barang, 0, ',', '.'),
                $barang->stok,
                ucfirst($barang->jenis)
            ]));
        }

        $writer->close();
    }
}
