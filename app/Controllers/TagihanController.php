<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TagihanModel;
use App\Models\MahasiswaModel;
use App\Models\PeriodeModel;
use App\Models\JenisTagihanModel;
use App\Models\PaymentModel;
use App\Models\MahasiswaPaymentModel;
use App\Models\RiwayatBayarModel;

class TagihanController extends BaseController
{
    public function __construct()
    {
        $this->tagihanModel = new TagihanModel();
        $this->periodeModel = new PeriodeModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->jenisTagihanModel = new JenisTagihanModel();
        $this->paymentModel = new PaymentModel();
        $this->mahasiswaPaymentModel = new MahasiswaPaymentModel();
        $this->riwayatBayarModel = new RiwayatBayarModel();
    }
    public function index()
    {
        return redirect()->to('tagihan/data-bendahara');
    }

    public function dataBendahara()
    {
        $dataPeriode = $this->periodeModel->orderBy('id', 'DESC')->findAll();
        $dataJenisTagihan = $this->jenisTagihanModel->get()->getResult();
        $context = [
            'data_periode' => $dataPeriode,
            'data_jenis_tagihan' => $dataJenisTagihan,
        ];
        return view('tagihan/data-bendahara', $context);
    }

    public function tambah()
    {
        $dataPeriode = $this->periodeModel->orderBy('id', 'DESC')->findAll();
        $dataJenisTagihan = $this->jenisTagihanModel->get()->getResult();

        $context = [
            'data_periode' => $dataPeriode,
            'data_jenis_tagihan' => $dataJenisTagihan,
        ];
        return view('tagihan/tambah', $context);
    }

    public function cek()
    {
        $filters = $this->request->getPost();
        $cekTagihanSpesifik = $this->tagihanModel->cekTagihanSpesifik($filters);

        return $this->response->setJSON($cekTagihanSpesifik);
    }

    public function create()
    {
        $formData = $this->request->getPost();

        $tagihanInsert = array();
        $mahasiswaPeriodeIds = $formData['mahasiswa_periode_ids'];

        for ($i=0; $i < count($mahasiswaPeriodeIds); $i++) {
            $tagihanInsert[$i]['mahasiswa_periode_id'] = (int) $mahasiswaPeriodeIds[$i];
            $tagihanInsert[$i]['jenis_tagihan_id'] = $formData['jenis_tagihan_id'];
            $tagihanInsert[$i]['biaya'] = $formData['biaya'];
            $tagihanInsert[$i]['status'] = 'belum';
        }


        $insertedTagihan = $this->tagihanModel->insertBatch($tagihanInsert);
        $context = [
            'success' => false,
        ];

        if ($insertedTagihan) {
            $context['success'] = true;
        }

        return $this->response->setJSON($context);
    }

    public function delete()
    {
        $tagihanId = $this->request->getGet('id');
        $tagihanData = $this->tagihanModel->where('id', $tagihanId)->find($tagihanId);
        $context = [
            'success' => false,
        ];
        if ($tagihanData->status != 'lunas') {
            $tagihanStatusDelete = $this->tagihanModel->delete($tagihanData->id);
            $context['success'] = true;
        }

        return $this->response->setJSON($context);
    }

    public function dataBendaharaJsonDT()
    {
        $filters = $this->request->getGet('filters');
        $tagihanData = $this->tagihanModel->getDataJsonDT($filters);
    }


    public function dataMahasiswa()
    {
        
        

        $mahasiswa_id = $this->session->get('data')['mahasiswa_id'];

        $dataPeriode = $this->periodeModel->orderBy('id', 'DESC')->findAll();
        $dataJenisTagihan = $this->jenisTagihanModel->get()->getResult();
        $payment = $this->paymentModel->findAll();
        $context = [
            'data_periode' => $dataPeriode,
            'data_jenis_tagihan' => $dataJenisTagihan,
            'daftar_pembayaran' => $payment,
        ];

        return view('tagihan/data-mahasiswa', $context);
    }

    public function dataMahasiswaJsonDT()
    {
        $filters = $this->request->getGet('filters');
        $mahasiswa_id = session()->get('data')['mahasiswa_id'];
        $tagihanData = $this->tagihanModel->getDataJsonDT($filters, $mahasiswa_id);
    }

    public function infoMahasiswa()
    {
        $tagihanId = $this->request->getGet('id');
        $data = $this->tagihanModel->getInfoTagihanMahasiswa($tagihanId);
        helper('tagihan');
        $data->biaya = rupiah($data->biaya);
        $data->periode = "{$data->periode} {$data->tahun}";
        $context = [
            'success' => true,
            'data' => $data
        ];
        return $this->response->setJSON($context);
    }

    public function bayar()
    {
        $formData = $this->request->getPost();
        $mahasiswaPayment = $this->mahasiswaPaymentModel
        ->getFromTagihan($formData);
        $riwayatInsert = [
            'tagihan_id' => $formData['tagihan_id'],
            'mahasiswa_payment_id' => $mahasiswaPayment->mahasiswa_payment_id,
            'waktu_bayar' => date("Y-m-d H:i:s"),
        ];


        $this->riwayatBayarModel->insert($riwayatInsert);

        $tagihanData = $this->tagihanModel->find($formData['tagihan_id']);
        $tagihanData->status = "lunas";
        $this->tagihanModel->save($tagihanData);

        $context = [
            'success' => true,
        ];

        return $this->response->setJSON($context);
    }

    public function dataPimpinan()
    {
        
        $dataPeriode = $this->periodeModel->orderBy('id', 'DESC')->findAll();
        $dataJenisTagihan = $this->jenisTagihanModel->get()->getResult();
        $context = [
            'data_periode' => $dataPeriode,
            'data_jenis_tagihan' => $dataJenisTagihan,
        ];
        return view('tagihan/data-pimpinan', $context);
    }

    public function dataPimpinanJsonDT()
    {
        $filters = $this->request->getGet('filters');
        $tagihanData = $this->tagihanModel->getDataJsonDT($filters);
    }

    public function grafikPimpinan()
    {
        $filters = $this->request->getGet('filters');
        $tagihanData = $this->tagihanModel->getDataJsonDT($filters);
    }



    public function daftarSeluruhTagihan()
    {
        return view('tagihan/daftar-seluruh-tagihan');
    }
    

    

    
    


    public function fetchDaftarTagihanSoloToJSON()
    {
        if ($this->session->get('role') != 'mahasiswa') {
            return $this->response->setJSON(array('success' => false));
        }
        $mahasiswa_id = $this->session->get('data')['mahasiswa_id'];

        $tagihanData = $this->tagihanModel->getAllDataToJSON($mahasiswa_id);
        return $tagihanData;
    }
}
