<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\UserModel;

class MahasiswaController extends BaseController
{
    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
    }

    /**
     * redirect ke dashboard.
     */
    public function index()
    {
        return reidrect()->to('/');
    }

    /**
     * menampilkan halaman mahasiswa.
     * tampilan CRUD mahasiswa.
     */
    public function data()
    {
        return view('mahasiswa/data');
    }

    /**
     * menyediakan data json yang digunakan untuk
     * menampilkan table menggunakan plugin datatables.
     *
     * @return  json:datatable  seluruh data mahasiswa
     */
    public function dataJsonDT()
    {
        // mendapatkan data json datatable
        $context = $this->mahasiswaModel->getDataJsonDT();

        return $context;
    }

    /**
     * menyediakan detail data mahasiswa.
     *
     * @param int $id id mahasiswa
     *
     * @return json detail data mahasiswa
     */
    public function show($id = null)
    {
        // simpan id ke variable $id
        $id = $this->request->getGet('id');
        // temukan data mahasiswa berdasarkan id
        $data = $this->mahasiswaModel->find($id);

        $context = [
            'success' => true,
            'data' => $data,
        ];

        // lempar nilai json ke view
        return $this->response->setJSON($context);
    }

    /**
     * link untuk keperluan tambah atau ubah.
     * digunakan ketika menggunakan modal bootstrap.
     *
     * @return json response sukses atau gagal
     */
    public function createUpdate()
    {
        $id = $this->request->getPost('id');

        $context = ['success' => false];

        if ($id) {
            $context = $this->update();
        } else {
            $context = $this->create();
        }

        // lempar nilai json ke view
        return $this->response->setJSON($context);
    }

    /**
     * fungsi menambah data mahasiswa.
     *
     * @return array kembalikan ke method createUpdate
     */
    public function create()
    {
        $formData = $this->request->getPost();
        $dataInsert = [
            'nim' => $formData['nim'],
            'nama' => $formData['nama'],
            'semester_berjalan' => $formData['semester_berjalan'],
            'angkatan' => $formData['angkatan'],
            'prodi_id' => $formData['prodi_id'],
        ];

        $userModel = new UserModel();
        $user = [
            'username' => $formData['nim'],
            'password' => 'asd',
            'role_id' => 'mahasiswa',
        ];
        // simpan data user yang ada di variable $user
        $userModel->insert($user);
        // simpan nilai id data mahasiswa yang baru di simpan kedalam variable.
        $dataInsert['user_id'] = $userModel->getInsertID();
        // simpan data mahasiswa yang ada di variable $dataInsert
        $this->mahasiswaModel->insert($dataInsert);
        $context = [
            'success' => false,
        ];
        // cek data mahasiswa berhasil disimpan
        if ($this->mahasiswaModel->getInsertID()) {
            $context['success'] = true;
        }

        return $context;
    }

    /**
     * fungsi mengubah data mahasiswa.
     *
     * @param int $id id mahasiswa yang akan diubah
     *
     * @return array kembalikan ke method createUpdate
     */
    public function update($id = null)
    {
        $formData = $this->request->getPost();
        $dataUpdate = [
            'nim' => $formData['nim'],
            'nama' => $formData['nama'],
            'semester_berjalan' => $formData['semester_berjalan'],
            'angkatan' => $formData['angkatan'],
        ];
        // ubah data mahasiswa
        $updateStatus = $this->mahasiswaModel->update($formData['id'], $dataUpdate);
        $context = [
            'success' => false,
        ];

        if ($updateStatus) {
            $context['success'] = true;
        }

        return $context;
    }

    /**
     * fungsi menghapus data mahasiswa.
     *
     * @param int $id id mahasiswa yang akan dihapus
     *
     * @return json response sukses atau gagal menghapus data
     */
    public function delete($id = null)
    {
        $id = $this->request->getGet('id');

        // hapus data mahasiswa berdasarkan id
        $deleteStatus = $this->mahasiswaModel->delete($id);

        $context = [
            'success' => false,
        ];

        if ($deleteStatus) {
            $context['success'] = true;
        }

        // lempar nilai json ke view
        return $this->response->setJSON($context);
    }
}
