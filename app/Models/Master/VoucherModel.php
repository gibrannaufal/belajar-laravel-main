<?php

namespace App\Models\Master;

use App\Repository\ModelInterface;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\RecordSignature;
use App\Models\Master\CustomerModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class VoucherModel extends Model implements ModelInterface
{
    use SoftDeletes, RecordSignature, HasRelationships, HasFactory;

    /**
    * Menentukan nama tabel yang terhubung dengan Class ini
    *
    * @var string
    */
   protected $table = 'm_voucher';

   /**
    * Menentukan primary key, jika nama kolom primary key adalah "id",
    * langkah deklarasi ini bisa dilewati
    *
    * @var string
    */
   protected $primaryKey = 'id_voucher';

   /**
    * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
    *
    * @var bool
    */
   public $timestamps = true;

   protected $attributes = [

   ];

   protected $fillable = [
       'id_promo',
       'id_customer',
       'nominal',
       'jumlah',
       'periode_mulai',
       'periode_selesai',
       'status',
       'catatan'

   ];

   
   
   /**
    * Menampilkan foto voucher dalam bentuk URL
    *
    * @return string
    */
   public function fotoUrl() {
       if(empty($this->foto)) {
           return asset('assets/img/no-image.png');
       } 
       return asset($this->foto);
   }
  
 
   public function getById(int $id): object
   {
       return $this->find($id);
   }
   public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
   {    
    
       $voucher = $this->query()
           ->leftJoin('m_customer', 'm_voucher.id_customer', '=', 'm_customer.id')
           ->leftJoin('m_promo', 'm_voucher.id_promo', '=', 'm_promo.id_promo')
           ->select('m_voucher.*', 'm_customer.nama as customer','m_promo.nama as promo');
           
       if (!empty($filter['customer'])) {
           $voucher->where('m_customer.nama', 'LIKE', '%'.$filter['customer'].'%');
       }
       
       $sort = $sort ?: 'm_voucher.id_voucher DESC';
       $voucher->orderByRaw($sort);
   
       $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false ;
       $voucherPaginated = $voucher->paginate($itemPerPage)->appends('sort', $sort);
   
       return $voucherPaginated;
   }
   

   public function store(array $payload) {
       return $this->create($payload);
   }

   public function edit(array $payload, int $id) {
       return $this->find($id)->update($payload);
   }

   public function drop($id) {
   
       return $this->find($id)->delete();
   }

   public function updateStatus($id) 
    {
        $voucher = $this->find($id);
        $voucher->status = 0;
        $voucher->save();
        return $voucher;
    }
    
}
