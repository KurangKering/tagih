<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MahasiswaModel;
use App\Models\TagihanModel;
use App\Models\UserModel;
class MahasiswaController extends ResourceController
{
    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
    }

    public function index()
    {
        return view('mahasiswa/data');
    }
    public function data()
    {
        return view('mahasiswa/data');
    }

    public function dataJsonDT($value = '')
    {
        $context = $this->mahasiswaModel->getDataJsonDT();
        return $context;
    }

    public function daftarTagihanSolo()
    {
        if ($this->session->get('role') != 'mahasiswa') {
            return redirect()->to('login');
        }

        $mahasiswaId = $this->session->get('mahasiswa_id');
        $tagihanModel = new TagihanModel();
        $tagihanData = $tagihanModel->getAll($mahasiswaId);
    }


    public function bayarTagihan()
    {

        if ($this->session->get('role') != 'mahasiswa') {
            return redirect()->to('login');
        }

        $mahasiswaId = $this->session->get('mahasiswa_id');
        $tagihanModel = new TagihanModel();
        $tagihanData = $tagihanModel->getBelumBayar($mahasiswaId);
        $message = '';
        if ($tagihanData) {
            $tagihanData->status = 'lunas';
            $message = 'berhasil';
        } else {
            $message = 'tidak ada tagihan';
        }

        $context = array(
            'success' => true,
            'message' => $message,
        );


        return $this->response->setJSON($context);
    }

    public function show($id = null)
    {
        $id = $this->request->getGet('id');
        $data = $this->mahasiswaModel->find($id);

        $context = [
            'success' => true,
            'data' => $data,
        ];

        return $this->response->setJSON($context);
    }

    public function createUpdate()
    {
        $id = $this->request->getPost('id');

        $context = ['success' => false];

        if ($id) {
            $context = $this->update();
        } else {
            $context = $this->create();
        }

        return $this->response->setJSON($context);
    }

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
            'role_id'  => 'mahasiswa',
        ];
        $userModel->insert($user);

        $dataInsert['user_id'] = $userModel->getInsertID();

        $this->mahasiswaModel->insert($dataInsert);
        $context = [
            'success' => false,
        ];

        if ($this->mahasiswaModel->getInsertID()) {
            $context['success'] = true;
        }

        return $context;
    }


    public function update($id = null)
    {
        $formData = $this->request->getPost();
        $dataUpdate = [

            'nim' => $formData['nim'],
            'nama' => $formData['nama'],
            'semester_berjalan' => $formData['semester_berjalan'],
            'angkatan' => $formData['angkatan'],
        ];

        $updateStatus = $this->mahasiswaModel->update($formData['id'], $dataUpdate);
        $context = [
            'success' => false,
        ];

        if ($updateStatus) {
            $context['success'] = true;
        }

        return $context;
    }

    public function delete($id = null)
    {
        $id = $this->request->getGet('id');

        $deleteStatus = $this->mahasiswaModel->delete($id);

        $context = [
            'success' => false,
        ];

        if ($deleteStatus) {
            $context['success'] = true;
        }

        return $this->response->setJSON($context);
    }
}
