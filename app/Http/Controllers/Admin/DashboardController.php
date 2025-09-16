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

        return view('admin.dashboard', compact('stats'));
    }
}
