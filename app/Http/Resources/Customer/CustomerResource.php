<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'id' => $this->id,
            'nama' => $this->nama,
            'email' => $this->email,
            'fotoUrl' => $this->fotoUrl(),
            'phone_number' => $this->phone_number,
            'date_of_birth' => $this->date_of_birth,
            'is_verified' => $this->is_verified,
            'is_verified_txt' => $this->isVerified(),
        ];
    }
}
