<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Helpers\Master\DiskonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Diskon\CreateRequest;
use App\Http\Resources\Diskon\DiskonResources;
use App\Http\Resources\Diskon\DiskonCollection;
use Illuminate\Pagination\LengthAwarePaginator;


class DiskonController extends Controller
{
    private $diskon;
    
    public function __construct()
    {
        $this->diskon = new DiskonHelper();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ['nama' => $request->nama ?? ''];
        $listUser = $this->diskon->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');
        $listDiskon = $this->diskon->diskon();

      
        // Loop untuk memasukkan atribut diskon ke dalam setiap objek UserModel
        foreach ($listUser as $user) {
            $userDiskon = null;
            // Mencari array diskon yang cocok berdasarkan id_user
            foreach ($listDiskon as $index => $diskon) {
                if ($user->id == $diskon['id_user']) {
       
                    $userDiskon[] = $diskon; // Menambahkan diskon ke dalam array $userDiskon
                }
               
            }
            // Menambahkan atribut diskon ke dalam objek UserModel
            $user->setAttribute('diskon', $userDiskon);
            
        }
       
        return response()->success(new DiskonCollection($listUser));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        
        $dataInput = $request->only(['id_user', 'id_promo', 'status']);
  

        $dataCust = $this->diskon->create($dataInput);
        
        if (!$dataCust['status']) {
            return response()->failed($dataCust['error'], 422);
        }

        return response()->success([], 'Data Diskon berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataCust = $this->diskon->getById($id);

        if (empty($dataCust)) {
            return response()->failed(['Data Diskon tidak ditemukan']);
        }

        return response()->success(new DiskonResources($dataCust));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(CreateRequest $request)
    {
       

        $dataInput = $request->only(['id_diskon', 'id_user', 'id_promo', 'status']);
        
        $dataCust = $this->diskon->update($dataInput, $dataInput['id_diskon']);
        
        if (!$dataCust['status']) {
            return response()->failed($dataCust['error'], 422);
        }

        return response()->success([], 'Data Diskon berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataCust = $this->diskon->delete($id);

        if (!$dataCust) {
            return response()->failed(['Mohon maaf data Diskon tidak ditemukan']);
        }

        return response()->success($dataCust);
    }
}
