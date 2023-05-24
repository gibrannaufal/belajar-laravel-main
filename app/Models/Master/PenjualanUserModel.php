<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class PenjualanUserModel extends Model
{
    use HasRelationships;
    /**
    * Menentukan nama tabel yang terhubung dengan Class ini
    *
    * @var string
    */
   protected $table = 't_order';
     /**
    * Menentukan primary key, jika nama kolom primary key adalah "id",
    * langkah deklarasi ini bisa dilewati
    *
    * @var string
    */
   protected $primaryKey = 'id_order';
   protected $attributes = [

   ];
   protected $fillable = [
     
   ];
   public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
   {
      
       $orderUser = $this->query()
       ->leftJoin('user_auth', 't_order.id_user', '=', 'user_auth.id')
       ->select(
           'user_auth.nama as nama', 't_order.tanggal as tanggal',
           't_order.total_bayar as total_bayar'
       );
    //    dd($orderUser->get());
       
       if (!empty($filter['nama'])) {
           $orderUser->where('user_auth.nama', 'LIKE', '%'.$filter['nama'].'%');
       }
       if (isset($filter['bulan'])) {
           $bulan = $filter['bulan'];
           $bulanFormat = explode('-', $bulan);
           $tahun = $bulanFormat[0];
           $bulan = $bulanFormat[1];
           $orderUser->whereYear('t_order.tanggal', '=', $tahun)
                     ->whereMonth('t_order.tanggal', '=', $bulan);
       }
    
   $sort = $sort ?: 'id DESC';
     $data = $orderUser->join('user_auth as u', 'u.id', '=', 't_order.id_user')
           ->select('u.nama as nama', 'u.id as id')
           ->selectRaw('DATE_FORMAT(t_order.tanggal, "%Y-%m-%d") AS tanggal')
           ->selectRaw('SUM(t_order.total_bayar) AS total_bayar')
           ->groupBy('id','nama', 'tanggal')
           ->orderBy('u.nama')
           ->orderBy('tanggal')
           ->orderByRaw($sort)
           ->get();
    //   dd($data);

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
       $resultData = array();

       if (!empty($data)) {
        foreach ($data as $item) {
            $name = $item->nama;
            $date = $item->tanggal;
            $total = $item->total_bayar;
    
            // Cek jika kategori belum ada dalam resultData, tambahkan
            if (!isset($resultData[$name])) {
                $resultData[$name] = array(
                    'nama' => $name,
                    'tanggal' => array(),
                    'totalsum' => 0
                );
            }
    
            // Total semua transaksi per nama
            $resultData[$name]['totalsum'] += $total;
    
            // Total per tanggal
            if (isset($resultData[$name]['tanggal'][$date])) {
                $resultData[$name]['tanggal'][$date] += $total;
            } else {
                $resultData[$name]['tanggal'][$date] = $total;
            }
        }
    }
        
       
       $combinedArray = [
           
              0 => $grandTotal,
              1 => $totalsemuatgl
          
         
       ];
       
   $result =  array_values($resultData);

           return response()->json([
               "links" => $result,
               "total" => [
                   $combinedArray
               ]
           ]);
       
   }
}
