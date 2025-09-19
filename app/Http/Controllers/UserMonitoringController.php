<?php

namespace App\Http\Controllers;

use App\Models\MonitoringBarang;
use Illuminate\Http\Request;

class UserMonitoringController extends Controller
{
    public function index()
    {
        $monitorings = MonitoringBarang::with(['barang' => function($query) {
            $query->withoutTrashed(); // Jika menggunakan soft deletes
        }])->whereHas('barang')->get();
        return view('user.monitoring.index', compact('monitorings'));
    }
}
