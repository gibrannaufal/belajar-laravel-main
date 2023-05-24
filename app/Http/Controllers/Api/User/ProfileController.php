<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\User\UserHelper;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\DetailResource;


class ProfileController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = new UserHelper();
    }

    public function show(int $id)
    {
        $dataUser = $this->user->getById($id);

        if (empty($dataUser)) {
            return response()->failed(['Data user tidak ditemukan']);
        }

        return response()->success(new DetailResource($dataUser));
    }

   
    public function update(UpdateRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/User/UpdateRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }
        $dataInput = $request->only(['email', 'nama', 'password', 'id', 'foto','user_roles_id','phone_number']);
        $dataUser = $this->user->update($dataInput, $dataInput['id']);

        if (!$dataUser['status']) {
            return response()->failed($dataUser['error']);
        }

        return response()->success(new UserResource($dataUser['data']), 'Data user berhasil diedit');
    }

}
