<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class TempController extends Controller
{
    public function temp()
    {
        return view('admin.temp');

    }

    public function tempStore(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:100',
            'harga_barang' => 'required|numeric',
            'jenis' => 'required|in:atk,cetak,tinta',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'

        ]);
    }

    public function tempShow()
    {
        return view('admin.temp-show');
    }

    public function tempEdit()
    {
        return view('admin.temp-edit');

    }

    public function tempUpdate(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:100',
            'harga_barang' => 'required|numeric|min:0',
            'jenis' => 'required|in:atk,cetak,tinta',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);
    }

    public function tempDestroy()
    {
        return redirect()->route('admin.temp.index');
    }
}