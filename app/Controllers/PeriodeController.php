<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PeriodeModel;
use App\Models\MahasiswaModel;
use App\Models\MahasiswaPeriodeModel;
use App\Models\TagihanModel;
use App\Models\JenisTagihanModel;

class PeriodeController extends BaseController
{
    public function __construct()
    {
        $this->periodeModel = new PeriodeModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->mahasiswaPeriodeModel = new MahasiswaPeriodeModel();
        $this->jenisTagihanModel = new JenisTagihanModel();
        $this->tagihanModel = new TagihanModel();
    }
    public function index()
    {
    }

    public function data()
    {
        return view('periode/data');
    }


    public function createUpdate()
    {
        $id = $this->request->getPost('id');

        $context = [
            'success' => false
        ];

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
        $data = [

            'periode' => $formData['periode'],
            'tahun' => $formData['tahun'],
            'waktu_mulai' => date('Y-m-d H:i:s'),
        ];
        $this->periodeModel->insert($data);
        $periodeId = $this->periodeModel->getInsertID();

        $dataMahasiswa = $this->mahasiswaModel->where('status', 'active')->findAll();

        $mahasiswaPeriodeInsert = array();
        $mahasiswaUpdate = array();

        foreach ($dataMahasiswa as $index => $mahasiswa) {
            $mahasiswaPeriodeInsert[$index]['mahasiswa_id'] = $mahasiswa->id;
            $mahasiswaPeriodeInsert[$index]['periode_id'] = $periodeId;
            $mahasiswaPeriodeInsert[$index]['semester'] = $mahasiswa->semester_berjalan + 1;
            $mahasiswaPeriodeInsert[$index]['status'] = 'active';

            $mahasiswaUpdate[$index]['id'] = $mahasiswa->id;
            $mahasiswaUpdate[$index]['semester_berjalan'] = $mahasiswaPeriodeInsert[$index]['semester'];
        }

        $this->mahasiswaPeriodeModel->insertBatch($mahasiswaPeriodeInsert);
        $updateMahasiswaStatus = $this->mahasiswaModel->updateBatch($mahasiswaUpdate, 'id');

        if (isset($formData['tagihan_spp'])) {
            $mahasiswaIds = array_column($dataMahasiswa, 'id');


            $mahasiswaPeriodeIds = $this->mahasiswaPeriodeModel
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->where('periode_id', $periodeId)
            ->findColumn('id');

            $tagihanSPPInsert = array();
            $jenisTagihan = $this->jenisTagihanModel->where('nama', 'SPP')->first();

            foreach ($dataMahasiswa as $key => $mahasiswa) {
                $tagihanSPPInsert[$key]['mahasiswa_periode_id'] = $mahasiswaPeriodeIds[$key];
                $tagihanSPPInsert[$key]['jenis_tagihan_id'] = $jenisTagihan['id'];
                $tagihanSPPInsert[$key]['biaya'] = $jenisTagihan['biaya'];
                $tagihanSPPInsert[$key]['status'] = 'belum';
            }
            $this->tagihanModel->insertBatch($tagihanSPPInsert);
        }

        $context = [
            'success' => false
        ];

        if ($periodeId) {
            $context['success'] = true;
        }

        return $context;
    }


    public function delete($id = null)
    {
        $id = $this->request->getGet('id');

        $periodeData = $this->periodeModel->find($id);
        $periodeDiatas = $this->periodeModel->where('id > ', $id)->first();
        if ($periodeDiatas) {
            $context = [
                'success' => false,
            ];


            return $this->response->setJSON($context);
        }
        $mahasiswaData = $this->mahasiswaModel->getMahasiswaByPeriode($periodeData->id);

        $mahasiswaUpdate = array();

        foreach ($mahasiswaData as $index => $mahasiswa) {
            $semesterBerjalan = $mahasiswa->semester_berjalan -=1;
            $semesterBerjalan = $semesterBerjalan == 0 ? null : $semesterBerjalan;

            $mahasiswaUpdate[$index]['id'] = $mahasiswa->id;
            $mahasiswaUpdate[$index]['semester_berjalan'] = $semesterBerjalan;
        }

        $deleteStatus = $this->periodeModel->delete($id);

        if ($deleteStatus) {
            $updateMahasiswaStatus = $this->mahasiswaModel->updateBatch($mahasiswaUpdate, 'id');
        }


        $context = [
            'success' => false,
        ];

        if ($deleteStatus) {
            $context['success'] = true;
        }

        return $this->response->setJSON($context);
    }
    
    public function dataJsonDT($value = '')
    {
        $mahasiswaJSON = $this->periodeModel->getDataToJSON();
        return $mahasiswaJSON;
    }
}
