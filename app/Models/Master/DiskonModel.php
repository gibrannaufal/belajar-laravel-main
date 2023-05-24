<?php

namespace App\Models\Master;

use App\Repository\ModelInterface;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class DiskonModel extends Model implements ModelInterface
{
    use SoftDeletes, RecordSignature, HasRelationships, HasFactory;

     /**
     * Menentukan nama tabel yang terhubung dengan Class ini
     *
     * @var string
     */
    protected $table = 'm_diskon';

    /**
     * Menentukan primary key, jika nama kolom primary key adalah "id",
     * langkah deklarasi ini bisa dilewati
     *
     * @var string
     */
    protected $primaryKey = 'id_diskon';

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;

    protected $attributes = [

    ];

    protected $fillable = [
        'id_user',
        'id_promo',
        'status',
    ];
    public function getPromo()
    {   
        $diskonList = DiskonModel::all();
        $promoList = [];

        foreach($diskonList as $diskon){
            $promo = [
                'id_diskon' => $diskon['id_diskon'],
                'id_user' => $diskon['id_user'],
                'id_promo' => $diskon['id_promo'],
                'status' => $diskon['status']
            ];
            array_push($promoList, $promo);
        }
 
        return $promoList;
    }


    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $diskon = $this->query();

        if (!empty($filter['nama'])) {
            $diskon->where('nama', 'LIKE', '%'.$filter['nama'].'%');
        }

        $sort = $sort ?: 'id_diskon DESC';
        $diskon->orderByRaw($sort);
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;
        
        return $diskon->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(int $id): object
    {
        return $this->find($id);
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
}
