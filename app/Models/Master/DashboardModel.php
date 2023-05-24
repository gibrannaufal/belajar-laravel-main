<?php

namespace App\Models\Master;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class DashboardModel extends Model
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
      
       $orderUser = $this->query()->select(
             't_order.tanggal as tanggal',
           't_order.total_bayar as total_bayar'
       );
      
       
       if (!empty($filter['nama'])) {
           $orderUser->where('user_auth.nama', 'LIKE', '%'.$filter['nama'].'%');
          
       }
       
       if (isset($filter['bulan'])) {
           $month = $filter['bulan'];
           $bulanFormat = explode('-', $month);
          
           $tahun = isset($bulanFormat[0]) ? $bulanFormat[0] : 0;
           $month = isset($bulanFormat[1]) ? $bulanFormat[1] : 0;
           $date = isset($bulanFormat[2]) ? $bulanFormat[2] : 0;
           $orderUser->whereYear('t_order.tanggal', '=', $tahun);
       }

       $orders = $orderUser->get()->toArray();
        $monthlyTotals = [];

        foreach ($orders as $order) {
            $tanggal = explode('-', $order['tanggal']);
            $bulan = intval($tanggal[1]);

            // Jika belum ada total bayar untuk bulan tersebut, inisialisasi dengan 0
            if (!isset($monthlyTotals[$bulan])) {
                $monthlyTotals[$bulan] = 0;
            }

            // Tambahkan total bayar pada bulan tersebut
            $monthlyTotals[$bulan] += $order['total_bayar'];
        }
    // Array bulan dengan indeks Januari hingga Desember
    $monthlyData = [
        'January' => isset($monthlyTotals[1]) ? $monthlyTotals[1] : 0,
        'February' => isset($monthlyTotals[2]) ? $monthlyTotals[2] : 0,
        'March' => isset($monthlyTotals[3]) ? $monthlyTotals[3] : 0,
        'April' => isset($monthlyTotals[4]) ? $monthlyTotals[4] : 0,
        'May' => isset($monthlyTotals[5]) ? $monthlyTotals[5] : 0,
        'June' => isset($monthlyTotals[6]) ? $monthlyTotals[6] : 0,
        'July' => isset($monthlyTotals[7]) ? $monthlyTotals[7] : 0,
        'August' => isset($monthlyTotals[8]) ? $monthlyTotals[8] : 0,
        'September' => isset($monthlyTotals[9]) ? $monthlyTotals[9] : 0,
        'October' => isset($monthlyTotals[10]) ? $monthlyTotals[10] : 0,
        'November' => isset($monthlyTotals[11]) ? $monthlyTotals[11] : 0,
        'December' => isset($monthlyTotals[12]) ? $monthlyTotals[12] : 0,
    ];
       
        // Clone objek query untuk bulan ini
        $queryBulanIni = clone $orderUser;
        $bulanini = $queryBulanIni->whereMonth('t_order.tanggal', '=', $month)->get()->sum('total_bayar');

        // Clone objek query untuk bulan lalu
        $queryBulanLalu = clone $orderUser;
        $bulanSebelumnya =  $month - 1;
        $bulanlalu = $queryBulanLalu->whereMonth('t_order.tanggal', '=', $bulanSebelumnya)->get()->sum('total_bayar');

         // Clone objek query untuk tanggal ini
         $queryToday = clone $orderUser;
      
         $today = $queryToday->whereDate(DB::raw("DAY(t_order.tanggal)"), $date)
         ->whereMonth('t_order.tanggal', '=', $month)
         ->get()
         ->sum('total_bayar');
    
         // Clone objek query untuk kemarin
         $queryYesterday = clone $orderUser;
         $prevDay = $date - 1;
         $yesterday = $queryYesterday->whereMonth('t_order.tanggal', '=',  $month)
         ->whereDate(DB::raw("DAY(t_order.tanggal)"), $prevDay)->get()
         ->sum('total_bayar');
 
    return response()->json([
              $monthlyData,$bulanlalu, $bulanini, $today, $yesterday
        ]);
       
   }

}
