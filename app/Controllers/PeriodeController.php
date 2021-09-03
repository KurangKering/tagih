<?php

namespace App\Controllers;

use App\Models\JenisTagihanModel;
use App\Models\MahasiswaModel;
use App\Models\MahasiswaPeriodeModel;
use App\Models\PeriodeModel;
use App\Models\TagihanModel;

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
        return redirect()->to('/');
    }

    /**
     * menu periode. menampilkan halaman periode.
     */
    public function data()
    {
        return view('periode/data');
    }

    /**
     * link untuk keperluan tambah atau ubah.
     * digunakan ketika menggunakan modal bootstrap.
     *
     * @return json response sukses atau gagal
     */
    public function createUpdate()
    {
        // berisi id , bisa ada bisa tidak
        $id = $this->request->getPost('id');

        $context = [
            'success' => false,
        ];
        if ($id) {
            $context = $this->update();
        } else {
            $context = $this->create();
        }

        return $this->response->setJSON($context);
    }

    /**
     * fungsi menambah periode. memulai periode baru.
     *
     * @return array kembalikan ke method createUpdate
     */
    public function create()
    {
        // tampung seluruh post data
        $formData = $this->request->getPost();
        // siapkan array untuk data periode yang akan disimpan
        $data = [
            'periode' => $formData['periode'],
            'tahun' => $formData['tahun'],
            'waktu_mulai' => date('Y-m-d H:i:s'),
        ];
        // simpan data
        $this->periodeModel->insert($data);
        // dapatkan id periode yang baru di simpan.
        $periodeId = $this->periodeModel->getInsertID();
        // temukan semua mahasiswa yang statusnya aktif.
        $dataMahasiswa = $this->mahasiswaModel->where('status', 'active')->findAll();
        // variable penampung data mahasiswa-periode
        $mahasiswaPeriodeInsert = [];
        // variable penampung data mahasiswa yang akan diubah semesternya.
        $mahasiswaUpdate = [];

        // untuk setiap data mahasiswa
        foreach ($dataMahasiswa as $index => $mahasiswa) {
            // siapkan data mahasiswa periode yang akan disimpan
            $mahasiswaPeriodeInsert[$index]['mahasiswa_id'] = $mahasiswa->id;
            $mahasiswaPeriodeInsert[$index]['periode_id'] = $periodeId;
            $mahasiswaPeriodeInsert[$index]['semester'] = $mahasiswa->semester_berjalan + 1;
            $mahasiswaPeriodeInsert[$index]['status'] = 'active';

            // siapkan data yang akan diubah dari data mahasiswa. yaitu semester
            $mahasiswaUpdate[$index]['id'] = $mahasiswa->id;
            $mahasiswaUpdate[$index]['semester_berjalan'] = $mahasiswaPeriodeInsert[$index]['semester'];
        }

        // simpan secara massal data mahasiswa periode.
        $this->mahasiswaPeriodeModel->insertBatch($mahasiswaPeriodeInsert);
        // ubah secara massal data mahasiswa
        $updateMahasiswaStatus = $this->mahasiswaModel->updateBatch($mahasiswaUpdate, 'id');

        // jika user menceklis tagihan spp,
        if (!empty($formData['tagihan_spp'])) {
            // tampung id seluruh mahasiswa yang baru saja memulai periode perkuliahan
            $mahasiswaIds = array_column($dataMahasiswa, 'id');
            // tampung id seluruh mahasiswa-periode yang baru disimpan.
            $mahasiswaPeriodeIds = $this->mahasiswaPeriodeModel
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->where('periode_id', $periodeId)
            ->findColumn('id');

            // siapkan array penampung data tagihan spp yang akan disimpan
            $tagihanSPPInsert = [];
            // tampung data jenis tagihan yang namanya spp.
            $jenisTagihan = $this->jenisTagihanModel->where('nama', 'SPP')->first();

            // untuk setiap mahasiswa yang tadi diubah
            foreach ($dataMahasiswa as $key => $mahasiswa) {
                // tampung data tagihan untuk disimpan ke database.
                $tagihanSPPInsert[$key]['mahasiswa_periode_id'] = $mahasiswaPeriodeIds[$key];
                $tagihanSPPInsert[$key]['jenis_tagihan_id'] = $jenisTagihan['id'];
                $tagihanSPPInsert[$key]['biaya'] = $jenisTagihan['biaya'];
                $tagihanSPPInsert[$key]['status'] = 'belum';
            }
            // simpan secara massal data tagihan
            $this->tagihanModel->insertBatch($tagihanSPPInsert);
        }

        $context = [
            'success' => false,
        ];

        if ($periodeId) {
            $context['success'] = true;
        }

        return $context;
    }

    /**
     * menghapus periode.
     *
     * @param int $id id periode
     *
     * @return json pesan sukses delete atau gagal
     */
    public function delete($id = null)
    {
        // tampung id periode
        $id = $this->request->getGet('id');

        // temukan periode berdasarkan id yang ditampung
        $periodeData = $this->periodeModel->find($id);
        // temukan periode sesudahnya.
        $periodeDiatas = $this->periodeModel->where('id > ', $id)->first();
        // jika ada periode sesudah periode ini,
        if ($periodeDiatas) {
            $context = [
                'success' => false,
            ];
            // kirim json gagal menghapus.
            return $this->response->setJSON($context);
        }
        // tampung data mahasiswa di periode itu.
        $mahasiswaData = $this->mahasiswaModel->getMahasiswaByPeriode($periodeData->id);
        // siapkan penampung untuk mengubah semester mahasiswa di periode itu -1
        $mahasiswaUpdate = [];
        // untuk setiap data mahasiswa di periode tersebut
        foreach ($mahasiswaData as $index => $mahasiswa) {
            // kurangkan 1 semester
            $semesterBerjalan = --$mahasiswa->semester_berjalan;
            // jika semester sudah 0, jadikan null
            $semesterBerjalan = 0 == $semesterBerjalan ? null : $semesterBerjalan;

            $mahasiswaUpdate[$index]['id'] = $mahasiswa->id;
            $mahasiswaUpdate[$index]['semester_berjalan'] = $semesterBerjalan;
        }
        // hapus data periode
        $deleteStatus = $this->periodeModel->delete($id);
        // jika periode telah berhasil dihapus
        if ($deleteStatus) {
            // ubah data semester yang ada di table mahasiswa
            $updateMahasiswaStatus = $this->mahasiswaModel->updateBatch($mahasiswaUpdate, 'id');
        }

        $context = [
            'success' => false,
        ];

        if ($deleteStatus) {
            $context['success'] = true;
        }
        // kirim response json
        return $this->response->setJSON($context);
    }

    /**
     * menyediakan data json yang digunakan untuk
     * menampilkan table menggunakan plugin datatables.
     *
     * @return  json:datatable  seluruh periode
     */
    public function dataJsonDT()
    {
        // mengambil json datable dari model periode =
        $mahasiswaJSON = $this->periodeModel->getDataToJSON();
        // mengirim json datatable ke response.
        return $mahasiswaJSON;
    }
}
