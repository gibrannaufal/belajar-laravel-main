<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\DashboardModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $dashboardMenu;
    
    public function __construct()
    {
        $this->dashboardMenu = new DashboardModel();
    }
    public function index(Request $request)
    {
        $filter = [
            'bulan' => $request->filter ?? '0000-00-00'
        ];
        // dd( $request->filter);
        $listdashboardMenu = $this->dashboardMenu->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');
        $dashboardMenuData = json_decode($listdashboardMenu->getContent(), true);
        
        return response()->success($dashboardMenuData);
    }
}
