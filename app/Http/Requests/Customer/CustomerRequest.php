<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;
class CustomerRequest extends FormRequest
{
    use ConvertsBase64ToFiles;
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
            'email' => "required|email|unique:m_customer,email,{$this->id}", // Validasi email unik berdasarkan data di tabel m_customer
            'phone_number' => 'numeric',
            'foto' => 'nullable|file|image',
            'date_of_birth' => 'required'
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
