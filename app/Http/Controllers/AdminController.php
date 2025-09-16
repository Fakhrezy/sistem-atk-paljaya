<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total' => \App\Models\Barang::count(),
            'atk' => \App\Models\Barang::where('jenis', 'atk')->count(),
            'cetak' => \App\Models\Barang::where('jenis', 'cetak')->count(),
            'tinta' => \App\Models\Barang::where('jenis', 'tinta')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
