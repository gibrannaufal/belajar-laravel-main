<?php

namespace App\Models\Master;

use stdClass;
use App\Repository\ModelInterface;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class PromoModel extends Model implements ModelInterface
{
    use SoftDeletes, RecordSignature, HasRelationships, HasFactory;

    /**
    * Menentukan nama tabel yang terhubung dengan Class ini
    *
    * @var string
    */
    protected $table = 'm_promo';

    /**
     * Menentukan primary key, jika nama kolom primary key adalah "id",
     * langkah deklarasi ini bisa dilewati
     *
     * @var string
     */
    protected $primaryKey = 'id_promo';
   

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;

    protected $attributes = [
        
    ];

    protected $fillable = [
        'nama',
        'type',
        'diskon',
        'nominal',
        'kadaluarsa',
        'syarat_ketentuan',
        'foto'
    ];

    public function fotoUrl() {
        if(empty($this->foto)) {
            return asset('assets/img/no-image.png');
        } 
        return asset($this->foto);
    }
    // memilih nominal atau diskon
    public function promoHuruf() {
        if(empty($this->diskon)) {
            return $this->nominal;
        } 
        return $this->diskon;
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $promo = $this->query();
        if (!empty($filter['nama'])) {
            $promo->where('nama', 'LIKE', '%'.$filter['nama'].'%');
        }

        $sort = $sort ?: 'id_promo DESC';
        $promo->orderByRaw($sort);
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;
        
        return $promo->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(int $id): object
    {
        return $this->find($id);
    }

    public function store(array $payload)
    {

        return $this->create($payload);
    }

    public function edit(array $payload, int $id)
    {
        return $this->find($id)->update($payload);
    }

    public function drop(int $id)
    {
        return $this->find($id)->delete();
    }
}
