<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\MahasiswaPeriodeModel;
use App\Models\PeriodeModel;

class MahasiswaPeriodeController extends BaseController
{
    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->mahasiswaPeriodeModel = new MahasiswaPeriodeModel();
        $this->periodeModel = new PeriodeModel();
    }

    public function index()
    {
        return redirect()->to('/');
    }

    /**
     * menu mahasiswa periode.
     * berisi data mahasiswa yang sedang berkuliah pada
     * periode berjalan dan berlalu.
     *
     * @return view halaman mahasiswa periode
     */
    public function data()
    {
        // simpan seluruh data periode ke variable dataPeriode
        $dataPeriode = $this->periodeModel->orderBy('id', 'DESC')->findAll();
        // mendapatkan seluruh semester yang sedang berjalan.
        $dataSemester = $this->mahasiswaPeriodeModel->getSemesterDistinct();
        //filter by periode_id
        $pilihPeriodeId = $this->request->getGet('periode_id');
        // cek periode ada atau tidak
        $checkPeriodeAda = $this->periodeModel->where('id', $pilihPeriodeId)->get()->getRow();
        // jika periode yang dipilih tidak ada di database
        if ($pilihPeriodeId && !$checkPeriodeAda) {
            // redirect ke halaman mahasiswa-periode
            return redirect()->to('mahasiswa-periode');
        }
        $context = [
            'data_periode' => $dataPeriode,
            'pilihan_periode' => $pilihPeriodeId,
            'data_semester' => $dataSemester,
        ];

        return view('mahasiswa-periode/data', $context);
    }

    /**
     * menyediakan data json yang digunakan untuk
     * menampilkan table menggunakan plugin datatables.
     *
     * @return  json:datatable  seluruh data mahasiswa bersama periode
     */
    public function dataJsonDT()
    {
        // tampung seluruh filter
        $filters = $this->request->getGet('filters');
        // tampung data json berdasarkan filter untuk ditampilkan ke datatable
        $context = $this->mahasiswaPeriodeModel->getDataJsonDT($filters);
        // kembalikan data berupa json
        return $context;
    }
}
