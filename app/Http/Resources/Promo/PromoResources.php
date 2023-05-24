<?php

namespace App\Http\Resources\Promo;

use Illuminate\Http\Resources\Json\JsonResource;

class PromoResources extends JsonResource
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
            'id_promo' => $this->id_promo,
            'nama' => $this->nama,
            'type' => $this->type,
            'promoHuruf' => $this->promoHuruf(),
            'kadaluarsa' => $this->kadaluarsa,
            'syarat_ketentuan' => $this->syarat_ketentuan,
            'fotoUrl' => $this->fotoUrl(),
            'diskon' => $this->diskon,
            'nominal' => $this->nominal,
          
        ];
    }
}
