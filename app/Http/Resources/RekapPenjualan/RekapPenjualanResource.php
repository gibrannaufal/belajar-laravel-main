<?php

namespace App\Http\Resources\RekapPenjualan;

use Illuminate\Http\Resources\Json\JsonResource;

class RekapPenjualanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      
        return [
            'id_order' => $this->id_order,
            'no_struk' => $this->no_struk,
            'id_user' => $this->id_user,
            'data_customer' => $this->getCustomerInfo($this->id_user),
            'tanggal' => $this->tanggal,
            'data_detail' => $this->getDetailInfo($this->id_order),
            'id_voucher' => $this->id_voucher,
            'id_diskon' => $this->id_diskon,
            'diskon' => $this->diskon,
            'potongan' => $this->potongan,
            'total_bayar' => $this->total_bayar,
            'total_order' => $this->total_order,
            'status' => $this->status,
        ];
    }
}
