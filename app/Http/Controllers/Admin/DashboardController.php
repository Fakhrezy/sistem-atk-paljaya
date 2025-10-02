<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Barang::count(),
            'atk' => Barang::where('jenis', 'atk')->count(),
            'cetak' => Barang::where('jenis', 'cetak')->count(),
            'tinta' => Barang::where('jenis', 'tinta')->count(),
        ];

        // Data untuk chart barang dengan stok paling sedikit (10 barang teratas)
        $lowStockItems = Barang::select('nama_barang', 'stok', 'jenis')
            ->orderBy('stok', 'asc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'lowStockItems'));
    }
}
