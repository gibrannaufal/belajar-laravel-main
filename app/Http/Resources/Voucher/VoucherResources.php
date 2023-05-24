<?php

namespace App\Http\Resources\Voucher;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResources extends JsonResource
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
            'id_voucher' => $this->id_voucher,
            'id_promo' => $this->id_promo,
            'id_customer' => $this->id_customer,
            'customer' => $this->customer,
            'nominal' => $this->nominal,
            'fotoUrl' => $this->fotoUrl(),
            'promo' => $this->promo,
            'jumlah' => $this->jumlah,
            'periode_mulai' => $this->periode_mulai,
            'periode_selesai' => $this->periode_selesai,
            'status' => $this->status,
            'catatan' => $this->catatan,
        ];
    }
}
