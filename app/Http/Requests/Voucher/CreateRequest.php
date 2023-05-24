<?php

namespace App\Http\Requests\Voucher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class CreateRequest extends FormRequest
{
    use ConvertsBase64ToFiles; // Library untuk convert base64 menjadi File
    
    public $validator = null;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
     /**
     * inisialisasi key "foto" dengan value base64 sebagai "FILE"
     *
     * @return array
     */
    protected function base64FileKeys(): array
    {
        return [
            'foto' => 'fotoUser.jpg',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
            'id_customer' => 'required|numeric',
            'id_promo' => 'required|numeric', 
            'nominal' => 'numeric|required',
            'jumlah' => 'required|numeric|min:1| max:100',
            'status' => 'required',
            'catatan' => 'max:100',
            'info_voucher' => 'nullable',
            'periode_mulai' => 'required',
            'periode_selesai' => 'required',

        ];
    }
    /**
     * Setting custom attribute pesan error yang ditampilkan
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'id_customer' => 'Kolom customer',
            'id_promo' => 'Kolom promo',
            'status' => 'Kolom status',
            'jumlah' => 'Kolom Jumlah',
            'info_voucher' => 'Kolom info voucher',
            'periode awal' => 'Kolom periode awal',
            'periode_selesai' => 'Kolom periode selesai',
        ];
    }
    
    /**
     * Tampilkan pesan error ketika validasi gagal
     *
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
       $this->validator = $validator;
    }

}
