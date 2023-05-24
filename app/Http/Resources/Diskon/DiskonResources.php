<?php

namespace App\Http\Resources\Diskon;

use Illuminate\Http\Resources\Json\JsonResource;

class DiskonResources extends JsonResource
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
            'id' => $this->user_id,
            'nama' => $this->nama,
            'diskon' => $this->diskon,
            'fotoUrl' => $this->fotoUrl(),
        ];
    }
}
