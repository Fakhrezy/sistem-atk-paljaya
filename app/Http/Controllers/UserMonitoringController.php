<?php

namespace App\Http\Controllers;

use App\Models\MonitoringBarang;
use Illuminate\Http\Request;

class UserMonitoringController extends Controller
{
    public function index()
    {
        $monitorings = MonitoringBarang::with('barang')->get();
        return view('user.monitoring.index', compact('monitorings'));
    }
}
