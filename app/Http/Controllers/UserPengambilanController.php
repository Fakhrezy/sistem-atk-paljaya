<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Monitoring;

class UserPengambilanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:user']);
    }

    /**
     * Display a listing of available items for user to take directly
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

    /**
     * Show the form for creating a new pengambilan
     */
    public function create(Request $request)
    {
        $barang_id = $request->input('barang_id');
        $barang = Barang::findOrFail($barang_id);

        return view('user.pengambilan.create', compact('barang'));
    }

    /**
     * Store a newly created pengambilan in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'bidang' => 'required|in:umum,perencanaan,keuangan,operasional,lainnya',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        // Cek apakah stok mencukupi
        if ($barang->stok < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Stok tidak mencukupi. Stok tersedia: ' . $barang->stok]);
        }

        // Buat record monitoring
        Monitoring::create([
            'user_id' => auth()->id(),
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'bidang' => $request->bidang,
            'keterangan' => $request->keterangan,
            'tanggal_pengambilan' => now(),
        ]);

        // Update stok barang
        $barang->decrement('stok', $request->jumlah);

        return redirect()->route('user.pengambilan.index')
            ->with('success', 'Berhasil mengambil barang: ' . $barang->nama_barang . ' sebanyak ' . $request->jumlah . ' ' . $barang->satuan);
    }
}
