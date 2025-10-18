<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanTriwulan extends Model
{
    use HasFactory;

    protected $table = 'laporan_triwulan';

    protected $fillable = [
        'barang_id',
        'nama_barang',
        'satuan',
        'tahun',
        'triwulan',
        'saldo_akhir_sebelumnya',
        'jumlah_pengadaan',
        'harga_satuan',
        'jumlah_harga',
        'jumlah_pemakaian',
        'saldo_tersedia',
        'total_harga',
        'keterangan'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'jumlah_harga' => 'decimal:2',
        'total_harga' => 'decimal:2'
    ];

    /**
     * Relationship dengan model Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }

    /**
     * Get nama triwulan dalam bahasa Indonesia
     */
    public function getNamaTriwulanAttribute()
    {
        $namaTriwulan = [
            1 => 'Triwulan I (Januari - Maret)',
            2 => 'Triwulan II (April - Juni)',
            3 => 'Triwulan III (Juli - September)',
            4 => 'Triwulan IV (Oktober - Desember)'
        ];

        return $namaTriwulan[$this->triwulan] ?? 'Unknown';
    }

    /**
     * Get periode laporan
     */
    public function getPeriodeLaporanAttribute()
    {
        return "Tahun {$this->tahun} - {$this->nama_triwulan}";
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopePeriode($query, $tahun, $triwulan)
    {
        return $query->where('tahun', $tahun)->where('triwulan', $triwulan);
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Get data untuk grafik atau statistik
     */
    public static function getStatistikTriwulan($tahun)
    {
        return self::where('tahun', $tahun)
            ->selectRaw('
                triwulan,
                SUM(jumlah_pengadaan) as total_pengadaan,
                SUM(jumlah_pemakaian) as total_pemakaian,
                SUM(saldo_tersedia) as total_saldo_tersedia,
                SUM(total_harga) as total_nilai_persediaan
            ')
            ->groupBy('triwulan')
            ->orderBy('triwulan')
            ->get();
    }
}
