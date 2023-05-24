<?php

namespace App\Http\Requests\Promo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class UpdateRequest extends FormRequest
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
            'nama' => 'required|max:100',
            'foto' => 'nullable|file|image', // Validasi untuk upload file image saja, jika tidak ada perubahan foto user, isi key foto dengan NULL
            'type' => 'required|max:10',
            'nominal' => 'numeric|required_without:diskon|nullable',
            'diskon' => 'numeric|required_without:nominal|nullable',
            'kadaluarsa' => 'required',
            'syarat_ketentuan' => 'required',
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
            'nama' => 'Kolom Nama',
            'type' => 'Kolom type', 
            'syarat_ketentuan' => 'Kolom term and condition', 
            'kadaluarsa' => 'kolom Kadaluarsa'

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
