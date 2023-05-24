<?php
namespace App\Exports\Penjualan;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class PenjualanUserExport implements FromView
{
    use Exportable;

    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
       
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('generate.Excel.penjualanUser', [
            'data' => $this->data
        ]);
    }
}