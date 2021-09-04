<?php

namespace App\Controllers;

use App\Models\JenisTagihanModel;
use App\Models\MahasiswaModel;
use App\Models\MahasiswaPaymentModel;
use App\Models\PaymentModel;
use App\Models\PeriodeModel;
use App\Models\RiwayatBayarModel;
use App\Models\TagihanModel;

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
        return redirect()->to('/');
    }

    /**
     * menu data tagihan role bendahara.
     * menu ini berisi halaman data tagihan mahasiswa.
     */
    public function dataBendahara()
    {
        // dapatkan seluruh data periode
        $dataPeriode = $this->periodeModel->orderBy('id', 'DESC')->findAll();
        // dapatkan seluruh jenis tagihan
        $dataJenisTagihan = $this->jenisTagihanModel->get()->getResult();
        // dapatkan seluruh semester yang sedang berjalan
        $dataSemester = $this->tagihanModel->getSemesterDistinct() ?? [];
        $context = [
            'data_periode' => $dataPeriode,
            'data_jenis_tagihan' => $dataJenisTagihan,
            'data_semester' => $dataSemester,
        ];

        return view('tagihan/data-bendahara', $context);
    }

    /**
     * menu tambah tagihan role bendahara.
     */
    public function tambah()
    {
        // dapatkan seluruh data periode
        $dataPeriode = $this->periodeModel->orderBy('id', 'DESC')->findAll();
        // dapatkan seluruh jenis tagihan
        $dataJenisTagihan = $this->jenisTagihanModel->get()->getResult();

        $context = [
            'data_periode' => $dataPeriode,
            'data_jenis_tagihan' => $dataJenisTagihan,
        ];

        return view('tagihan/tambah', $context);
    }

    /**
     * method yang dipanggil ajax ketika mengecek
     * apakah mahasiswa pada periode tertentu belum
     * memiliki tagihan.
     *
     * @return json data mahasiswa yang belum ada tagihan tertentu di periode itu
     */
    public function cek()
    {
        // tampung post data
        $filters = $this->request->getPost();
        // cek mahasiswa2 yang belum ada tagihan jenis tertentu pada periode tertentu
        $cekTagihanSpesifik = $this->tagihanModel->cekTagihanSpesifik($filters);
        // kembalikan data berupa json ke view.
        return $this->response->setJSON($cekTagihanSpesifik);
    }

    /**
     * method proses tambah data tagihan.
     *
     * @return json sukses atau gagal menambah data
     */
    public function create()
    {
        // tampung post data
        $formData = $this->request->getPost();
        // siapkan penampung data tagihan yang akan diinsert
        $tagihanInsert = [];
        // tampung id mahasiswa-periode
        $mahasiswaPeriodeIds = $formData['mahasiswa_periode_ids'];
        // untuk id mahasiswa-periode
        for ($i = 0; $i < count($mahasiswaPeriodeIds); ++$i) {
            $tagihanInsert[$i]['mahasiswa_periode_id'] = (int) $mahasiswaPeriodeIds[$i];
            $tagihanInsert[$i]['jenis_tagihan_id'] = $formData['jenis_tagihan_id'];
            $tagihanInsert[$i]['biaya'] = $formData['biaya'];
            $tagihanInsert[$i]['status'] = 'belum';
        }
        // simpan data tagihan secara massal
        $insertedTagihan = $this->tagihanModel->insertBatch($tagihanInsert);
        $context = [
            'success' => false,
        ];

        if ($insertedTagihan) {
            $context['success'] = true;
        }
        // kembalikan data json ke ajax yang memanggil
        return $this->response->setJSON($context);
    }

    /**
     * method yang dipanggil ajax ketika menghapus data tagihan
     * tagihan yang sudah lunas tidak dapat dihapus.
     *
     * @return json sukses atau gagal menghapus data tagihan
     */
    public function delete()
    {
        // tampung id tagihan
        $tagihanId = $this->request->getGet('id');
        // temukan data tagihan terkait
        $tagihanData = $this->tagihanModel->where('id', $tagihanId)->find($tagihanId);
        $context = [
            'success' => false,
        ];
        // jika tagihan belum lunas
        if ('lunas' != $tagihanData->status) {
            // hapus tagihan
            $tagihanStatusDelete = $this->tagihanModel->delete($tagihanData->id);
            $context['success'] = true;
        }
        // kembalikan json ke ajax yang memanggil
        return $this->response->setJSON($context);
    }

    /**
     * method yang dipanggil ajax pada menu data tagihan role
     * bendahara untuk mendapatkan json data tagihan mahasiswa
     * sesuai dengan filter.
     *
     * @return  json:datatables data tagihan sesuai filter
     */
    public function dataBendaharaJsonDT()
    {
        // tampung filter
        $filters = $this->request->getGet('filters');
        // memanggil method di TagihanModel yang memproses dan mengembalikan json datatable
        $tagihanData = $this->tagihanModel->getDataJsonDT($filters);
        // kembalikan json datatable ke ajax yang memanggil.
        return $tagihanData;
    }

    /**
     * menu data tagihan mahasiswa yang login.
     * berisi seluruh tagihan mahasiswa tersebut.
     */
    public function dataMahasiswa()
    {
        // letakkan seluruh data periode dari database ke variable. urukan menurut id desc
        $dataPeriode = $this->periodeModel->orderBy('id', 'DESC')->findAll();
        // ambil seluruh data jenis tagihan dan letakkan ke variable
        $dataJenisTagihan = $this->jenisTagihanModel->get()->getResult();
        // isi variable payment berupa seluruh data jenis pembayaran
        $payment = $this->paymentModel->findAll();
        $context = [
            'data_periode' => $dataPeriode,
            'data_jenis_tagihan' => $dataJenisTagihan,
            'daftar_pembayaran' => $payment,
        ];

        return view('tagihan/data-mahasiswa', $context);
    }

    /**
     * method yang dipanggil ajax pada menu data tagihan role
     * mahasiswa untuk mendapatkan json data tagihan mahasiswa
     * tersebut sesuai dengan filter.
     */
    public function dataMahasiswaJsonDT()
    {
        // tampung filter data
        $filters = $this->request->getGet('filters');
        // tampung id mahasiswa yang sedang login
        $mahasiswaId = session()->get('data')['mahasiswa_id'];
        // proses mengambil data tagihan dari database untuk dikirim ke datatable
        $tagihanData = $this->tagihanModel->getDataJsonDT($filters, $mahasiswaId);
    }

    /**
     * method yang dipanggil ajax ketika user role mahasiswa
     * memencet tombol bayar tagihan.
     *
     * @return json berisi data tagihan yang akan dibayar
     */
    public function infoMahasiswa()
    {
        // tampung id tagihan
        $tagihanId = $this->request->getGet('id');
        // dapatkan info tagihan dari mahasiswa yang sedang login
        $data = $this->tagihanModel->getInfoTagihanMahasiswa($tagihanId);
        // load helper tagihan
        helper('tagihan');
        // format biaya sesuai penulisan yang benar.
        $data->biaya = rupiah($data->biaya);
        $data->periode = "{$data->periode} {$data->tahun}";
        $context = [
            'success' => true,
            'data' => $data,
        ];

        return $this->response->setJSON($context);
    }

    /**
     * method yang dipanggil ajax ketika user role mahasiswa
     * menekan tombol proses payment. tombol ini akan
     * melakukan update data tagihan menjadi lunas dan menambah
     * riwayat bayar pada table riwayat_bayar.
     *
     * @return json pesan sukses
     */
    public function bayar()
    {
        // tampung post data
        $formData = $this->request->getPost();
        // ambil data payment si mahasiswa terkait sesuai tagihan.
        $mahasiswaPayment = $this->mahasiswaPaymentModel
        ->getFromTagihan($formData);
        // variable penampung data riwayat bayar yang akan disimpan ke database
        $riwayatInsert = [
            'tagihan_id' => $formData['tagihan_id'],
            'mahasiswa_payment_id' => $mahasiswaPayment->mahasiswa_payment_id,
            'waktu_bayar' => date('Y-m-d H:i:s'),
        ];
        // simpan riwayat bayar
        $this->riwayatBayarModel->insert($riwayatInsert);
        // temukan data tagihan berdasarkan tagihan_id yang ada di post data
        $tagihanData = $this->tagihanModel->find($formData['tagihan_id']);
        // berikan nilai lunas
        $tagihanData->status = 'lunas';
        // simpan tagihan
        $this->tagihanModel->save($tagihanData);

        $context = [
            'success' => true,
        ];
        // kirim json ke view
        return $this->response->setJSON($context);
    }

    /**
     * menu data tagihan role pimpinan
     * menu ini berisi data tagihan mahasiswa. pada
     * menu ini terdapat beberapa filter yang digunakan
     * untuk mem-filter data tagihan.
     */
    public function dataPimpinan()
    {
        // dapatkan data periode. urutkan menurut id desc, simpan ke variable
        $dataPeriode = $this->periodeModel->orderBy('id', 'DESC')->findAll();
        // dapatkan seluruh semester yang berjalan
        $dataSemester = $this->tagihanModel->getSemesterDistinct() ?? [];
        // dapatkan jenis tagihan
        $dataJenisTagihan = $this->jenisTagihanModel->get()->getResult();
        $context = [
            'data_periode' => $dataPeriode,
            'data_jenis_tagihan' => $dataJenisTagihan,
            'data_semester' => $dataSemester,
        ];

        return view('tagihan/data-pimpinan', $context);
    }

    /**
     * method yang dipanggil datatable yang mengembalikan
     * json datatable data tagihan mahasiswa pada halaman
     * data tagihan role pimpinan.
     */
    public function dataPimpinanJsonDT()
    {
        // tampung filter
        $filters = $this->request->getGet('filters');
        // dapatkan data tagihan json datatable
        $tagihanData = $this->tagihanModel->getDataJsonDT($filters);

        return $tagihanData;
    }

    /**
     * menu laporan data tagihan role pimpinan.
     */
    public function grafikPimpinan()
    {
        // dapatkan data periode. urutkan menurut id desc, simpan ke variable
        $dataPeriode = $this->periodeModel->orderBy('id', 'DESC')->findAll();
        // dapatkan seluruh semester yang berjalan
        $dataSemester = $this->tagihanModel->getSemesterDistinct() ?? [];
        // dapatkan jenis tagihan
        $dataJenisTagihan = $this->jenisTagihanModel->get()->getResult();
        $context = [
            'data_periode' => $dataPeriode,
            'data_jenis_tagihan' => $dataJenisTagihan,
            'data_semester' => $dataSemester,
        ];

        return view('tagihan/grafik-pimpinan', $context);

    }

    public function grafikPimpinanJson()
    {
        $filters = $this->request->getGet('filters');
        
        $tagihanData = $this->tagihanModel->getTotalLunasBelum($filters);

        $isiData = [];
        $backgroundColor = [];
        $labels = [];

        foreach ($tagihanData as $index => $data) {
            if ($data->status == "lunas") {
                array_push($isiData, $data->jumlah);
                array_push($backgroundColor, "#63ed7a");
                array_push($labels, "Lunas");

            } elseif ($data->status == "belum") {
                array_push($isiData, $data->jumlah);
                array_push($backgroundColor, "#fc544b");
                array_push($labels, "Belum");
            }
        }

        $returnData = [];

        $returnData['datasets'] = array(
            array(
                'data' => $isiData,
                'backgroundColor' => $backgroundColor,
                'label' => 'Grafik Lunas dan Belum',
            ),
        );
        $returnData['labels'] = $labels;

        return $this->response->setJSON($returnData);

    }
}
