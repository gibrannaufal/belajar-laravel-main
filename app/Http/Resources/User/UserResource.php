<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'user_roles_id' => (string) $this->user_roles_id,
            'updated_security' => $this->updated_security,
            'akses' => $this->role->nama??''
        ];
    }
}
