<?php

namespace App\Http\Controllers;

use App\Models\KeranjangUsulan;
use App\Models\MonitoringPengadaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KeranjangUsulanController extends Controller
{
    public function index()
    {
        $items = KeranjangUsulan::with('barang')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.usulan.cart', compact('items'));
    }

    public function update(Request $request, $id)
    {
        $item = KeranjangUsulan::where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $item->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Usulan berhasil diperbarui'
        ]);
    }

    public function remove($id)
    {
        $item = KeranjangUsulan::where('user_id', Auth::id())
            ->findOrFail($id);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari usulan'
        ]);
    }

    public function clear()
    {
        KeranjangUsulan::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang usulan berhasil dikosongkan'
        ]);
    }

    public function submit()
    {
        try {
            $items = KeranjangUsulan::where('user_id', Auth::id())
                ->with('barang')
                ->get();

            if ($items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada item untuk diajukan'
                ]);
            }

            // Simpan setiap item ke monitoring_pengadaan
            foreach ($items as $item) {
                MonitoringPengadaan::create([
                    'user_id' => Auth::id(),
                    'barang_id' => $item->barang->kode_barang, // Using kode_barang from barang relation
                    'debit' => $item->jumlah,
                    'keterangan' => $item->keterangan,
                    'status' => 'proses',
                    'tanggal' => Carbon::now(),
                ]);
            }

            // Kosongkan keranjang usulan
            KeranjangUsulan::where('user_id', Auth::id())->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usulan pengadaan berhasil diajukan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengajukan usulan: ' . $e->getMessage()
            ]);
        }
    }
}
