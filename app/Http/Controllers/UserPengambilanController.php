<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class UserPengambilanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:user']);
    }

    /**
     * Display a listing of available items for user to add to cart
     */
    public function index(Request $request)
    {
        $query = Barang::query();
        $perPage = $request->input('per_page', 12);

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        // Filter hanya barang yang stoknya > 0
        $query->where('stok', '>', 0);

        // Order by nama barang
        $query->orderBy('nama_barang', 'asc');

        $barang = $query->paginate($perPage);

        // Get distinct jenis for filter dropdown
        $jenisBarang = Barang::where('stok', '>', 0)
            ->distinct()
            ->pluck('jenis')
            ->sort();

        return view('user.pengambilan.index', compact('barang', 'jenisBarang'));
    }
}
