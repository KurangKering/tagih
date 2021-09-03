<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MahasiswaModel;
use App\Models\MahasiswaPeriodeModel;
use App\Models\TagihanModel;
use App\Models\PeriodeModel;

class MahasiswaPeriodeController extends ResourceController
{
    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->mahasiswaPeriodeModel = new MahasiswaPeriodeModel();
        $this->periodeModel = new PeriodeModel();
    }

    public function index()
    {
        return redirect()->to(base_url('mahasiswa-periode/data'));
    }
    public function data()
    {
        $dataPeriode = $this->periodeModel->orderBy('id', 'DESC')->findAll();
        $dataSemester = $this->mahasiswaPeriodeModel->getSemesterDistinct();


        $pilihPeriodeId = $this->request->getGet('periode_id');
        $checkPeriodeAda = $this->periodeModel->where('id', $pilihPeriodeId)->get()->getRow();
        if ($pilihPeriodeId && !$checkPeriodeAda) {
            return redirect()->to('mahasiswa-periode');
        }
        $context = [
            'data_periode' => $dataPeriode,
            'pilihan_periode' => $pilihPeriodeId,
            'data_semester' => $dataSemester,
        ];
        return view('mahasiswa-periode/data', $context);
    }

   
    public function dataJsonDT($value = '')
    {
        $filters = $this->request->getGet('filters');
        $context = $this->mahasiswaPeriodeModel->getDataJsonDT($filters);
        return $context;
    }

    public function daftarTagihanSolo()
    {
        if ($this->session->get('role') != 'mahasiswa') {
            return redirect()->to('login');
        }

        $mahasiswa_id = $this->session->get('mahasiswa_id');
        $tagihanModel = new TagihanModel();
        $tagihanData = $tagihanModel->getAll($mahasiswa_id);
    }

    public function tampilBayarTagihan()
    {
    }

    public function bayarTagihan()
    {

        if ($this->session->get('role') != 'mahasiswa') {
            return redirect()->to('login');
        }

        $mahasiswa_id = $this->session->get('mahasiswa_id');
        $tagihanModel = new TagihanModel();
        $tagihanData = $tagihanModel->getBelumBayar($mahasiswa_id);
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
        ];

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
