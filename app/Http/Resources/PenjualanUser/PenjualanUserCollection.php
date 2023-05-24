<?php

namespace App\Http\Resources\PenjualanUser;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PenjualanUserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this->collection['meta']['getUrlRange']);
        return [
            'list' => $this->collection['links'], // otomatis mengikuti format UserResource
            'total' => $this->collection['total']
        ];
    }
}
