<?php

namespace App\Http\Resources\RekapPenjualan;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RekapPenjualanCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'list' => $this->collection, // otomatis mengikuti format PenjualanResource
            'meta' => [
                'links' => $this->getUrlRange(1, $this->lastPage()),
                'total' => $this->total()
            ]
        ];
    }
}
