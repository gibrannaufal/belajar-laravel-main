<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\RekapPenjualan\RekapPenjualanCollection;
use App\Models\Master\RekapPenjualanModel;
use Illuminate\Http\Request;

class RekapPenjualanController extends Controller
{
    private $penjualan;

    public function __construct()
    {
        $this->penjualan = new RekapPenjualanModel();
    }
    public function index(Request $request)
    {
        $filter = [
            'nama' => $request->nama ?? ''
        ];
        $penjualans = $this->penjualan->getAll($filter, 10, $request->page ?? '');

        return response()->success(new RekapPenjualanCollection($penjualans));
    }
}
