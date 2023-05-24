<?php

namespace App\Models\Master;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class RekapPenjualanModel extends Model
{
    use  HasRelationships;

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

    /**
        * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
        *
        * @var bool
        */
    public $timestamps = true;

    protected $attributes = [

    ];

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $penjualan = $this->query();

        if (!empty($filter['nama'])) {
            
        }

        $sort = $sort ?: 'id_order DESC';
        $penjualan->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false ;

        return $penjualan->paginate($itemPerPage)->appends('sort', $sort);
    }
    
    public function getCustomerInfo(int $id){
        $data_customer = array();
        $customer = DB::table('m_customer')->where('id', $id)->get();

        foreach($customer as $detailCustomer){
            $data_customer[] = [
                'nama_customer' => $detailCustomer->nama
            ];
        }

        return $data_customer;
    }
    
    public function getDetailInfo(int $id){
        $data_detail = array();
        $detail = DB::table('t_detail_order')->where('id_order', $id)->get();

        $no = 1;
        foreach($detail as $detailOrder){
            $data_detail[] = [
                'no' => $no,
                'id_detail' => $detailOrder->id_detail,
                'id_item' => $detailOrder->id_item,
                'nama' => $this->getMenuInfo($detailOrder->id_item, 'nama'),
                'harga' => $this->getMenuInfo($detailOrder->id_item, 'harga'),
                'total' => $detailOrder->total,
                'jumlah' => $detailOrder->jumlah
            ];
            $no++;
        }

        return $data_detail;
    }
    
    public function getMenuInfo(int $id, string $field){
        $menu = DB::table('m_item')->where('id', $id)->first();

        if($field == 'nama'){
            return $menu->nama;
        }else{
            return $menu->harga;
        }
    }
 
}
