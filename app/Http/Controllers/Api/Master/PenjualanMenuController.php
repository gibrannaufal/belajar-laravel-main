<?php

namespace App\Http\Controllers\Api\Master;


use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Master\PenjualanMenuModel;
use App\Exports\Penjualan\PenjualanMenuExport;
use App\Http\Resources\OrderMenu\OrderMenuCollection;

class PenjualanMenuController extends Controller
{
    private $orderMenu;
    
    public function __construct()
    {
        $this->orderMenu = new PenjualanMenuModel();
    }
    public function index(Request $request)
    {
        $filter = [
            'nama' => $request->nama ?? '',
            'bulan' => $request->bulan ?? '',
        ];
       
        $listOrderMenu = $this->orderMenu->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');
        $orderMenuData = json_decode($listOrderMenu->getContent(), true);
   
        return response()->success(new OrderMenuCollection($orderMenuData));
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
                'bulan' =>  $request->bulan,
                'content' =>  $orderMenuData['links'],
            ];

            // Load the view and convert it to HTML
            $view = view('generate.pdf.penjualan', compact('data'));
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
            'bulan' => $request->bulan,
            'content' => $orderMenuData['links'],
        ];

        // Generate Excel using Laravel-Excel
        return Excel::download(new PenjualanMenuExport($data), 'penjualanMenu.xlsx');
        
    }
}
