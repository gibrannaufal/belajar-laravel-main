<?php

namespace App\Http\Controllers\Api\Master;

use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Master\PenjualanUserModel;

use App\Exports\Penjualan\PenjualanUserExport;
use App\Http\Resources\PenjualanUser\PenjualanUserCollection;

class PenjualanUserController extends Controller
{
    private $orderMenu;
    
    public function __construct()
    {
        $this->orderMenu = new PenjualanUserModel();
    }
    public function index(Request $request)
    {
        $filter = [
            'nama' => $request->nama ?? '',
            'bulan' => $request->bulan ?? '',
        ];
        
        $listOrderMenu = $this->orderMenu->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');
        $orderMenuData = json_decode($listOrderMenu->getContent(), true);
   
        return response()->success(new PenjualanUserCollection($orderMenuData));
    }
    public function generatePDF(Request $request)
        {
            $filter = [
                'kategori' => $request->kategori ?? '',
                'bulan' => $request->bulan ?? '',
            ];

            $listOrderMenu = $this->orderMenu->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');
            $orderMenuData = json_decode($listOrderMenu->getContent(), true);
          
            $data = [
                'jmlHari' => $request->hari,
                'kategori' =>  $request->kategori,
                'bulan' => $request->bulan,
                'content' =>  $orderMenuData['links'],
            ];

            // Load the view and convert it to HTML
            $view = view('generate.pdf.penjualanUser', compact('data'));
            $html = $view->render();

            // Create a new Dompdf instance
            $dompdf = new Dompdf();

            // Load the HTML content
            $dompdf->loadHtml($html);

            // Set the paper size and orientation (landscape)
            $dompdf->setPaper('A4', 'landscape');

            // Render the PDF
            $dompdf->render();

            // Output the PDF to the browser
            $dompdf->stream('penjualanMenu.pdf');
    }
    public function generateExcel(Request $request)
    {
        $filter = [
            'kategori' => $request->kategori ?? '',
            'bulan' => $request->bulan ?? '',
        ];

        $listOrderMenu = $this->orderMenu->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');
        $orderMenuData = json_decode($listOrderMenu->getContent(), true);

        $data = [
            'jmlHari' => $request->hari,
            'kategori' =>  $request->kategori,
            'bulan' => $request->bulan,
            'content' =>  $orderMenuData['links'],
        ];

        // Generate Excel using Laravel-Excel
        return Excel::download(new PenjualanUserExport($data), 'penjualanUser.xlsx');
        
    }
}
