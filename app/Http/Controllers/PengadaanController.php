<?php

namespace App\Http\Controllers;
use App\Http\Controllers\KeranjangUsulan;

use App\Models\Pengadaan;
use App\Models\MonitoringBarang;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    public function create($monitoring_id)
    {
        $monitoring = MonitoringBarang::findOrFail($monitoring_id);
        return view('user.pengadaan.create', compact('monitoring'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'monitoring_barang_id' => 'required|exists:monitoring_barang,id',
            'tanggal' => 'required|date',
            'debit' => 'required|integer|min:1'
        ]);

        Pengadaan::create([
            'monitoring_barang_id' => $request->monitoring_barang_id,
            'tanggal' => $request->tanggal,
            'debit' => $request->debit,
            'status' => 'proses'
        ]);

        return redirect()->route('user.monitoring.index')->with('success', 'Usulan pengadaan berhasil dikirim');
    }

    public function storeBatch(Request $request)
    {
        $keranjangItems = KeranjangUsulan::where('user_id', auth()->id())->with('barang')->get();

        if ($keranjangItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang usulan kosong');
        }

        foreach ($keranjangItems as $item) {
            $monitoring = MonitoringBarang::where('id_barang', $item->barang_id)->first();

            if ($monitoring) {
                Pengadaan::create([
                    'monitoring_barang_id' => $monitoring->id,
                    'tanggal' => now(),
                    'debit' => $item->jumlah,
                    'status' => 'proses'
                ]);
            }
        }

        // Kosongkan keranjang setelah usulan dibuat
        KeranjangUsulan::where('user_id', auth()->id())->delete();

        return redirect()->route('user.monitoring.index')
            ->with('success', 'Semua usulan pengadaan berhasil dikirim');
    }
}
