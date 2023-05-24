<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Master\VoucherHelper;
use App\Http\Requests\Voucher\CreateRequest;
use App\Http\Requests\Voucher\UpdateRequest;
use App\Http\Resources\Voucher\VoucherResources;
use App\Http\Resources\Voucher\VoucherCollection;


class VoucherController extends Controller
{
    
    private $voucher;
    
    public function __construct()
    {
        $this->voucher = new VoucherHelper();
       
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [
            'customer' => $request->customer ?? '',
        ];
        $voucherdata = $this->voucher->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');
        $voucherdata->getCollection()->map(function ($item) {
            $item->fotoUrl = $item->fotoUrl();
            return $item;
        });
        return response()->success(new VoucherCollection($voucherdata));
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
         * pengaturan validasi bisa dilihat pada class app/Http/request/Customer/CustomerRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }
        
        $dataInput = $request->only(['id_promo', 'id_customer', 'nominal','jumlah','periode_mulai','periode_selesai','status','catatan']);
        $dataVoucher = $this->voucher->create($dataInput);
        
        if (!$dataVoucher['status']) {
            return response()->failed($dataVoucher['error'], 422);
        }

        return response()->success([], 'Data customer berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataVoucher = $this->voucher->getById($id);
       
        if (empty($dataVoucher)) {
            return response()->failed(['Data voucher tidak ditemukan']);
        }
        
        return response()->success(new VoucherResources($dataVoucher));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/Customer/CustomerRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $dataInput = $request->only(['id_voucher','id_promo', 'id_customer', 'nominal','periode_mulai','periode_selesai','jumlah','status','catatan']);
        $dataVoucher = $this->voucher->update($dataInput, $dataInput['id_voucher']);
        
        if (!$dataVoucher['status']) {
            return response()->failed($dataVoucher['error']);
        }

        return response()->success(new VoucherResources($dataVoucher['data']), 'Data customer berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataVoucher = $this->voucher->delete($id);

        if (!$dataVoucher) {
            return response()->failed(['Mohon maaf data voucher tidak ditemukan']);
        }

        return response()->success($dataVoucher);
    }

    /**
     * Mengubah Status menjadi non-aktif.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id)
    {
        
        $dataVoucher = $this->voucher->updateStatus($id);

        if (!$dataVoucher) {
            return response()->failed(['Mohon maaf data voucher tidak ditemukan']);
        }

        return response()->success($dataVoucher);
    }
}
