<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use App\Models\Barang;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Monitoring::with('barang');
        $perPage = $request->input('per_page', 10);

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id_monitoring', 'like', "%{$search}%")
                  ->orWhere('bidang', 'like', "%{$search}%")
                  ->orWhere('pengambil', 'like', "%{$search}%")
                  ->orWhereHas('barang', function($q) use ($search) {
                      $q->where('nama_barang', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai]);
        }

        $monitoring = $query->orderBy('tanggal', 'desc')->paginate($perPage);
        return view('admin.monitoring.index', compact('monitoring'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::all();
        return view('admin.monitoring.create', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pengambil' => 'required|string|max:255',
            'id_barang' => 'required|exists:barang,id_barang',
            'debit' => 'nullable|integer|min:0',
            'kredit' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Pastikan salah satu dari debit atau kredit diisi
        if (!$request->debit && !$request->kredit) {
            return back()->withErrors(['debit' => 'Debit atau Kredit harus diisi salah satu'])->withInput();
        }

        // Ambil data barang untuk menghitung saldo
        $barang = Barang::where('id_barang', $request->id_barang)->first();

        // Hitung saldo baru
        $debit = $request->debit ?? 0;
        $kredit = $request->kredit ?? 0;
        $saldoBaru = $barang->stok + $debit - $kredit;

        // Validasi saldo tidak boleh negatif
        if ($saldoBaru < 0) {
            return back()->withErrors(['kredit' => 'Stok tidak mencukupi untuk pengeluaran ini'])->withInput();
        }

        // Simpan monitoring dengan bidang otomatis dari user yang login
        Monitoring::create([
            'tanggal' => $request->tanggal,
            'bidang' => auth()->user()->bidang, // Otomatis dari user login
            'pengambil' => $request->pengambil,
            'id_barang' => $request->id_barang,
            'debit' => $debit,
            'kredit' => $kredit,
            'saldo' => $saldoBaru,
            'keterangan' => $request->keterangan,
        ]);

        // Update stok barang
        $barang->update(['stok' => $saldoBaru]);

        return redirect()->route('admin.monitoring')->with('success', 'Data monitoring berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Monitoring $monitoring)
    {
        return view('admin.monitoring.show', compact('monitoring'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Monitoring $monitoring)
    {
        $barang = Barang::all();
        return view('admin.monitoring.edit', compact('monitoring', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Monitoring $monitoring)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pengambil' => 'required|string|max:255',
            'id_barang' => 'required|exists:barang,id_barang',
            'debit' => 'nullable|integer|min:0',
            'kredit' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Pastikan salah satu dari debit atau kredit diisi
        if (!$request->debit && !$request->kredit) {
            return back()->withErrors(['debit' => 'Debit atau Kredit harus diisi salah satu'])->withInput();
        }

        // Kembalikan stok lama
        $barangLama = Barang::where('id_barang', $monitoring->id_barang)->first();
        $stokLama = $barangLama->stok - $monitoring->debit + $monitoring->kredit;

        // Hitung saldo baru
        $barang = Barang::where('id_barang', $request->id_barang)->first();
        $debit = $request->debit ?? 0;
        $kredit = $request->kredit ?? 0;

        // Jika barang berbeda, gunakan stok barang baru, jika sama gunakan stok yang sudah dikembalikan
        if ($monitoring->id_barang == $request->id_barang) {
            $saldoBaru = $stokLama + $debit - $kredit;
        } else {
            $saldoBaru = $barang->stok + $debit - $kredit;
            // Kembalikan stok barang lama
            $barangLama->update(['stok' => $stokLama]);
        }

        // Validasi saldo tidak boleh negatif
        if ($saldoBaru < 0) {
            return back()->withErrors(['kredit' => 'Stok tidak mencukupi untuk pengeluaran ini'])->withInput();
        }

        // Update monitoring dengan bidang otomatis dari user yang login
        $monitoring->update([
            'tanggal' => $request->tanggal,
            'bidang' => auth()->user()->bidang, // Otomatis dari user login
            'pengambil' => $request->pengambil,
            'id_barang' => $request->id_barang,
            'debit' => $debit,
            'kredit' => $kredit,
            'saldo' => $saldoBaru,
            'keterangan' => $request->keterangan,
        ]);

        // Update stok barang baru
        $barang->update(['stok' => $saldoBaru]);

        return redirect()->route('admin.monitoring')->with('success', 'Data monitoring berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Monitoring $monitoring)
    {
        // Kembalikan stok barang
        $barang = Barang::where('id_barang', $monitoring->id_barang)->first();
        $stokBaru = $barang->stok - $monitoring->debit + $monitoring->kredit;
        $barang->update(['stok' => $stokBaru]);

        $monitoring->delete();

        return redirect()->route('admin.monitoring')->with('success', 'Data monitoring berhasil dihapus');
    }
}
