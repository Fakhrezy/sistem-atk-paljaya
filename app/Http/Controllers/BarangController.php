<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MonitoringBarang;
use App\Models\MonitoringPengadaan;
use App\Exports\BarangExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();
        $perPage = $request->input('per_page', 10);

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('id_barang', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        $barang = $query->paginate($perPage);

        // Statistik barang berdasarkan jenis
        $stats = [
            'total' => Barang::count(),
            'atk' => Barang::where('jenis', 'atk')->count(),
            'cetak' => Barang::where('jenis', 'cetak')->count(),
            'tinta' => Barang::where('jenis', 'tinta')->count(),
        ];

        return view('admin.barang.index', compact('barang', 'stats'));
    }

    public function create()
    {
        return view('admin.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'satuan' => 'required',
            'harga_barang' => 'required|numeric',
            'jenis' => 'required|in:atk,cetak,tinta',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $path = $foto->store('barang', 'public');
            $data['foto'] = $path;
        }

        Barang::create($data);

        return redirect()->route('admin.barang')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $oldStok = $barang->stok; // Simpan stok lama

        $request->validate([
            'nama_barang' => 'required',
            'satuan' => 'required',
            'harga_barang' => 'required|numeric',
            'stok' => 'required|numeric|min:0',
            'jenis' => 'required|in:atk,cetak,tinta',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
                Storage::disk('public')->delete($barang->foto);
            }

            $foto = $request->file('foto');
            $path = $foto->store('barang', 'public');
            $data['foto'] = $path;
        }

        DB::beginTransaction();
        try {
            $barang->update($data);

            // Jika stok berubah, update saldo di monitoring barang dan pengadaan
            if ($oldStok != $request->stok) {
                $this->updateMonitoringBarangSaldo($barang->id_barang, $request->stok);
                $this->updateMonitoringPengadaanSaldo($barang->id_barang, $request->stok);
            }

            DB::commit();
            return redirect()->route('admin.barang')->with('success', 'Barang berhasil diupdate dan saldo monitoring telah diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.barang')->with('error', 'Gagal mengupdate barang: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        // Hapus foto jika ada
        if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
            Storage::disk('public')->delete($barang->foto);
        }

        $barang->delete();

        return redirect()->route('admin.barang')->with('success', 'Barang berhasil dihapus');
    }

    public function print(Request $request)
    {
        $query = Barang::query();

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('id_barang', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        $barang = $query->get();
        return view('admin.barang.print', compact('barang'));
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $jenis = $request->input('jenis');

        $filename = storage_path('app/public/exports/data-barang.xlsx');

        // Ensure the exports directory exists
        if (!file_exists(dirname($filename))) {
            mkdir(dirname($filename), 0777, true);
        }

        $export = new BarangExport($search, $jenis);
        $export->export($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    /**
     * Helper method to update saldo in monitoring barang table when stock changes
     */
    private function updateMonitoringBarangSaldo($idBarang, $newStok)
    {
        // Update saldo untuk monitoring barang dengan status 'diajukan' (belum diterima)
        MonitoringBarang::where('id_barang', $idBarang)
            ->where('status', 'diajukan')
            ->update([
                'saldo' => $newStok,
                'saldo_akhir' => DB::raw('saldo - kredit')
            ]);
    }

    /**
     * Helper method to update saldo_akhir in monitoring pengadaan table when stock changes
     */
    private function updateMonitoringPengadaanSaldo($idBarang, $newStok)
    {
        // Update saldo_akhir untuk semua monitoring pengadaan
        MonitoringPengadaan::where('barang_id', $idBarang)
            ->update([
                'saldo_akhir' => $newStok
            ]);
    }
}
