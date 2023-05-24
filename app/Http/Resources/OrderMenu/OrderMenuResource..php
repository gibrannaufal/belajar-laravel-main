<?php

namespace App\Http\Resources\OrderMenu;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderMenuResource extends JsonResource
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
            'food' => $this->nama,
            'drinks' => $this->tanggal,
            'snack' => $this->kategori,
        ];
    }
}
