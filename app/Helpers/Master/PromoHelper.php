<?php

namespace App\Helpers\Master;

use App\Models\Master\PromoModel;
use App\Repository\CrudInterface;

/**
 * Helper untuk manajemen promo
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_promo
 *
 * @author Wahyu Agung <wahyuagung26@gmail.com>
 */
class PromoHelper implements CrudInterface
{
    private $PromoModel;

    public function __construct()
    {
        $this->PromoModel = new PromoModel();
    }

    /**
     * Mengambil data promo dari tabel m_pro
     *
     * @author Wahyu Agung <wahyuagung26@gmail.com>
     *
     * @param  array 
     * @param integer 
     * @param string 
     *
     * @return object
     */
    public function getAll(array $filter = [], int $itemPerPage = 0, string $sort = ''): object
    {
        return $this->PromoModel->getAll($filter, $itemPerPage, $sort);
    }

    /**
     * Mengambil 1 data promo dari tabel m_promo
     *
     * @param  integer $id id dari tabel m_promo
     * 
     * @return void
     */
    public function getById(int $id): object
    {
        return $this->PromoModel->getById($id);
    }

    /**
     * method untuk menginput data baru ke tabel m_promo
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @param array $payload
     * $payload['nama'] = string
     * $payload['email] = string
     * $payload['is_verified] = string
     *
     * @return array
     */
    public function create(array $payload): array
    {
        try {
      
            if (!empty($payload['foto'])) {
                /**
                 * Parameter kedua ("gcs") digunakan untuk upload ke Google Cloud Service
                 * jika mau upload di server local, maka tidak usah pakai parameter kedua
                 */
                $foto = $payload['foto']->store('upload/formPromo');
                $destinationPath = public_path('upload/formPromo');
                $payload['foto']->move($destinationPath, $foto);
                $payload['foto'] = $foto;
            }else {
                unset($payload['foto']); // Jika foto kosong, hapus dari array agar tidak diupdate
            }
            // dd($payload);
            $promo = $this->PromoModel->store($payload);

            return [
                'status' => true,
                'data' => $promo
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * method untuk mengubah promo pada tabel m_promo
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @param array $payload
     * $payload['nama'] = string
     * $payload['email] = string
     * $payload['is_verified] = boolean
     *
     * @return void
     */
    public function update(array $payload, int $id): array
    {
        try {

          
            if (!empty($payload['foto'])) {
                /**
                 * Parameter kedua ("gcs") digunakan untuk upload ke Google Cloud Service
                 * jika mau upload di server local, maka tidak usah pakai parameter kedua
                 */
                $foto = $payload['foto']->store('upload/formpromo');
                $destinationPath = public_path('upload/formpromo');
                $payload['foto']->move($destinationPath, $foto);
                $payload['foto'] = $foto;
            }else {
                unset($payload['foto']); // Jika foto kosong, hapus dari array agar tidak diupdate
            }
            $this->PromoModel->edit($payload, $id);
            return [
                'status' => true,
                'data' => $this->getById($id)
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * Menghapus data promo dengan sistem "Soft Delete"
     * yaitu mengisi kolom deleted_at agar data tsb tidak
     * keselect waktu menggunakan Query
     *
     * @param  integer $id id dari tabel m_promo
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $this->PromoModel->drop($id);
            return true;
        } catch (\Throwable $th) {
         
            return false;
        }
    }
}
