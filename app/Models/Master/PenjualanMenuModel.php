<?php

namespace App\Models\Master;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class PenjualanMenuModel extends Model
{
    use HasRelationships;
     /**
     * Menentukan nama tabel yang terhubung dengan Class ini
     *
     * @var string
     */
    protected $table = 't_detail_order';
      /**
     * Menentukan primary key, jika nama kolom primary key adalah "id",
     * langkah deklarasi ini bisa dilewati
     *
     * @var string
     */
    protected $primaryKey = 'id_detail';
    protected $attributes = [

    ];
    protected $fillable = [
      
    ];
    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
       
        $orderMenu = $this->query()
        ->leftJoin('t_order', 't_detail_order.id_order', '=', 't_order.id_order')
        ->leftJoin('m_item', 't_detail_order.id_item', '=', 'm_item.id')
        ->select(
            'm_item.nama as nama','m_item.kategori as kategori',
            't_order.tanggal as tanggal','t_order.total_bayar as total_bayar'
        );
        // dd($orderMenu->toSql());
        
        if (!empty($filter['kategori'])) {
            $orderMenu->where('m_item.kategori', 'LIKE', '%'.$filter['kategori'].'%');
           
        }
        
        if (isset($filter['bulan'])) {
            $bulan = $filter['bulan'];
            $bulanFormat = explode('-', $bulan);
            $tahun = $bulanFormat[0];
            $bulan = $bulanFormat[1];
            $orderMenu->whereYear('t_order.tanggal', '=', $tahun)
                      ->whereMonth('t_order.tanggal', '=', $bulan);
        }
     
    $sort = $sort ?: 'id DESC';
      $data = $orderMenu->join('t_order as o', 'o.id_order', '=', 't_detail_order.id_order')
            ->join('m_item as i', 'i.id', '=', 't_detail_order.id_item')
            ->select('i.nama as nama', 'i.kategori as tipe', 't_detail_order.id_detail as id')
            ->selectRaw('DATE_FORMAT(o.tanggal, "%Y-%m-%d") AS tanggal')
            ->selectRaw('SUM(o.total_bayar) AS total_bayar')
            ->groupBy('i.nama', 'i.kategori', 'tanggal','t_detail_order.id_detail')
            ->orderBy('i.nama')
            ->orderBy('tanggal')
            ->orderByRaw($sort)
            ->get();
            
            $total_tanggal = [];

    foreach ($data as $item) {
        $tanggal = $item->tanggal;
        $total_bayar = $item->total_bayar;

        if (!isset($total_tanggal[$tanggal])) {
            $total_tanggal[$tanggal] = [
                'tanggal' => $tanggal,
                'total' => 0
            ];
        }

        $total_tanggal[$tanggal]['total'] += $total_bayar;
    }

    $grandTotal = array_values($total_tanggal);
    $totalsemuatgl = array_sum(array_column($grandTotal, 'total'));
 

           // Mengubah data pagination menjadi format yang diinginkan
        $resultData = array(
            'food' => array(),
            'drink' => array(),
            'snack' => array()
        );

        if (!empty($data)) {
            foreach ($data as $item) {
                $name = $item->nama;
                $category = $item->tipe;
                $date = $item->tanggal;
                $total = $item->total_bayar;
        
               // Add item to appropriate category array
                switch ($category) {
                    case 'food':
                        if (!isset($resultData['food'][$name])) {
                            $resultData['food'][$name] = array(
                                'nama' => $name,
                                'kategori' => $category,
                                'tanggal' => array(),
                                'totalsum' => 0
                            );
                        }
                        // Total semua transaksi per nama
                        $resultData['food'][$name]['totalsum'] += $total;
                     
                                            // total per tanggal
                        if (isset($resultData['food'][$name]['tanggal'][$date])) {
                            $resultData['food'][$name]['tanggal'][$date] += $total;
                        } else {
                            $resultData['food'][$name]['tanggal'][$date] = $total;
                        }
                        break;
                    case 'drink':
                        if (!isset($resultData['drink'][$name])) {
                            $resultData['drink'][$name] = array(
                                'nama' => $name,
                                'kategori' => $category,
                                'tanggal' => array(),
                                'totalsum' => 0
                            );
                        }
                         // Total semua transaksi per nama
                         $resultData['drink'][$name]['totalsum'] += $total;

                        //  total per tanggal
                        if (isset($resultData['drink'][$name]['tanggal'][$date])) {
                            $resultData['drink'][$name]['tanggal'][$date] += $total;
                        } else {
                            $resultData['drink'][$name]['tanggal'][$date] = $total;
                        }
                        break;
                    case 'snack':
                        if (!isset($resultData['snack'][$name])) {
                            $resultData['snack'][$name] = array(
                                'nama' => $name,
                                'kategori' => $category,
                                'tanggal' => array(),
                                'totalsum' => 0
                            );
                        }
                         // Total semua transaksi per nama
                         $resultData['snack'][$name]['totalsum'] += $total;

                        //  total per tanggal
                        if (isset($resultData['snack'][$name]['tanggal'][$date])) {
                            $resultData['snack'][$name]['tanggal'][$date] += $total;
                        } else {
                            $resultData['snack'][$name]['tanggal'][$date] = $total;
                        }
                        break;
                    default:
                        // Do nothing for unrecognized category
                        break;
                }
            }
             
        }
        $combinedArray = [
            
               0 => $grandTotal,
               1 => $totalsemuatgl
           
          
        ];
        
    $result = $resultData;
            return response()->json([
                "links" => [

                    'Food' => array_values($result['food']),
                    'Drink' => array_values($result['drink']),
                    'Snack' => array_values($result['snack']),
                ],
                "total" => [
                    $combinedArray
                ]
            ]);
        
    }

}
