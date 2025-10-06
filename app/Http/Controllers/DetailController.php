<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailMonitoringBarang;
use App\Models\Barang;
use App\Services\DetailMonitoringBarangService;

class DetailMonitoringBarangDebug extends Controller{
    protected $detailMonitoringService;

    public function __construct()
    {

    }
}
