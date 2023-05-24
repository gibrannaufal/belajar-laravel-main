<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\Master\PromoHelper;

use App\Http\Resources\Promo\PromoCollection;
use App\Http\Requests\Promo\CreateRequest;
use App\Http\Requests\Promo\UpdateRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Promo\PromoResources;
use App\Models\Master\PromoModel;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    protected $promo;

    public function __construct()
    {
        $this->promo = new PromoHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ['nama' => $request->nama ?? ''];
        $listPromo = $this->promo->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');
        $listPromo->getCollection()->map(function ($item) {
            $item->fotoUrl = $item->fotoUrl();
            return $item;
        });
        $listPromo->getCollection()->map(function ($item) {
            $item->promoHuruf = $item->promoHuruf();
            return $item;
        });
        return response()->success(new PromoCollection($listPromo));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
         /**
        * Menampilkan pesan error ketika validasi gagal
        * pengaturan validasi bisa dilihat pada class app/Http/request/item/CreateRequest
        */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }
        
        $dataInput = $request->only(['nama', 'type', 'diskon', 'foto', 'nominal', 'kadaluarsa', 'syarat_ketentuan']);
        // dd($dataInput['id']);
        
        $dataPromo = $this->promo->create($dataInput);
        
        if (!$dataPromo['status']) {
            return response()->failed($dataPromo['error'], 422);
        }
        
        return response()->success([], 'Data promo berhasil disimpan');
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataPromo = $this->promo->getById($id);

        if (empty($dataPromo)) {
            return response()->failed(['Data customer tidak ditemukan']);
        }
  
        return response()->success(new PromoResources($dataPromo));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PromoModel  $promoModel
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, PromoModel $promoModel)
    {
         /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/Customer/CustomerRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $dataInput = $request->only(['nama', 'type', 'diskon', 'foto', 'id_promo','nominal','kadaluarsa','syarat_ketentuan']);
        $dataPromo = $this->promo->update($dataInput, $dataInput['id_promo']);
        
        if (!$dataPromo['status']) {
            return response()->failed($dataPromo['error']);
        }

        return response()->success(new PromoResources($dataPromo['data']), 'Data promo berhasil disimpan');
    
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $dataPromo = $this->promo->delete($id);

        if (!$dataPromo) {
            return response()->failed(['Mohon maaf data item tidak ditemukan']);
        }

        return response()->success($dataPromo);
    }
}
