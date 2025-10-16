<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\DetailMonitoringBarang;
use App\Models\Barang;
use App\Services\DetailMonitoringBarangService;

class DetailMonitoringBarangControllerTemp extends Controller
{
    protected $detailMonitoringBarangService;

    public function __construct(DetailMonitoringBarangService $detailMonitoringBarangService)
    {
        $this->detailMonitoringBarangService = $detailMonitoringBarangService;
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        try{
            if($request->get('sync')){
                $this->detailMonitoringBarangService->syncAllData();
                return redirect()->route('admin.detail-monitoring-barang.index')->with('success', 'Data monitoring berhasil disinkronisasi!');
            }
            $filters = [
                'id_barang' => $request->get('id_barang'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'bidang' => $request->get('bidang'),
                'jenis' => $request->get('jenis'),
            ];

            $query = $this->detailMonitoringBarangService->getDetailMonitoring($filters);
            $detailMonitoring = $query->paginate(20)->appends($request->query());
        }
        catch(Exception $e){
            return redirect()->route('admin.ba
        }
}

