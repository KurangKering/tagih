<?php

namespace App\Models;

use CodeIgniter\Model;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\Codeigniter4Adapter;
class TagihanModel extends Model
{
	protected $table                = 'tagihan';
	protected $allowedFields        = [
		'mahasiswa_periode_id',
		'biaya',
		'jenis_tagihan_id',
		'status',
	];

	protected $returnType    = 'App\Entities\TagihanEntity';



	public function getAll($mahasiswa_id = null)
	{
		if ($mahasiswa_id) {
			return $this->where('mahasiswa_id', $mahasiswa_id)
			->get()->getRowArray();
		} else {
			return $this->get()->getResultArray();
		}
	}

	public function getDataJsonDT($filters= null, $mahasiswa_id=null)
	{
		$dt = new Datatables(new Codeigniter4Adapter());
		$query = "select 
		tagihan.id as tagihan_id, 
		mahasiswa.nim, 
		mahasiswa.nama, 
		mahasiswa_periode.semester, 
		tagihan.status, 
		tagihan.biaya, 
		periode.periode, 
		periode.tahun,
		jenis_tagihan.nama as jenis
		from tagihan 
		join mahasiswa_periode on tagihan.mahasiswa_periode_id = mahasiswa_periode.id 
		join mahasiswa on mahasiswa.id = mahasiswa_periode.mahasiswa_id 
		join periode on periode.id = mahasiswa_periode.periode_id
		join jenis_tagihan on tagihan.jenis_tagihan_id = jenis_tagihan.id";


		$filter_array = [];
		if ($filters) {
			$this->cleaningFilters($filters);

			if ($this->checkFilters($filters, 'periode_id')) {
				$filter_array['periode_id'] = $filters['periode_id'];
			}

			if ($this->checkFilters($filters, 'jenis_tagihan_id')) {
				$filter_array['jenis_tagihan_id'] = $filters['jenis_tagihan_id'];

			}

			if ($this->checkFilters($filters, 'status')) {
				$filter_array['tagihan.status'] = $filters['status'];

			}
			if ($mahasiswa_id != null) {
				$filter_array['mahasiswa.id'] = $mahasiswa_id;

			}
		}

		$where = array();
		foreach ($filter_array as $key => $value) {
			$where[] = $key . " = " . $value;
			
		}

		if (!empty($where)) {
			$query .= " where " . implode(' AND ', $where);
		}
		$dt->query($query);

		$dt->add('action', function($q) {
			return '';
		});

		helper('tagihan');

		$dt->edit('biaya', function($q) {
			return rupiah($q['biaya']);
		});

		
		echo $dt->generate();
	}

	private function checkFilters($array, $key) {
		return (isset($array[$key]) && $array[$key] != '');
	}

	private function cleaningFilters(&$array) {
		$array['periode_id'] = $array['periode_id'] != '' ? (int) $array['periode_id'] : '';
		$array['jenis_tagihan_id'] = $array['jenis_tagihan_id'] != '' ? (int) $array['jenis_tagihan_id'] : '';
		$array['status'] = in_array($array['status'], ['belum', 'lunas']) ? "'".$array['status']."'" : '';
	}

	public function cekTagihanSpesifik($filters)
	{
		$periode_id = $filters['periode_id'];
		$jenis_tagihan_id = $filters['jenis_tagihan_id'];


		$query = "select 
		mp.id as mp_id,
		mhs.nim,
		mhs.nama,
		mhs.semester_berjalan
		from mahasiswa_periode mp 
		join mahasiswa mhs on mp.mahasiswa_id = mhs.id
		join periode p on mp.periode_id = p.id and p.id = ?
		where mp.id not in (select tg.mahasiswa_periode_id
		from tagihan tg
		join mahasiswa_periode mp on mp.id = tg.mahasiswa_periode_id
		join periode p on p.id = mp.periode_id and periode_id = ?
		where tg.jenis_tagihan_id = ?  group by (mahasiswa_periode_id))";

		$db = \Config\Database::connect();
		$query = $db->query($query, [$periode_id, $periode_id, $jenis_tagihan_id]);

		return $query->getResult();
	}

	public function getInfoTagihanMahasiswa($tagihanId)
	{

		$sql = "select 
		t.id as tagihan_id,
		p.periode,
		p.tahun,
		jt.nama as jenis_tagihan,
		t.biaya
		from tagihan t
		join jenis_tagihan as jt on t.jenis_tagihan_id = jt.id
		join mahasiswa_periode mp on t.mahasiswa_periode_id = mp.id
		join periode p on p.id = mp.periode_id
		where t.id = ?
		";

		$db = \Config\Database::connect();
		$query = $db->query($sql, [$tagihanId]);
		return $query->getRow();
	}
	public function getBelumBayar($mahasiswa_id = null)
	{
		if ($mahasiswa_id) {
			return $this->where('mahasiswa_id', $mahasiswa_id)
			->where('status', 'belum')
			->get()->getRowArray();
		} else {
			return $this->where('status', 'belum')
			->get()->getResultArray();
		}
	}


	public function getTotalBelumBayar($mahasiswa_id)
	{
		return $this->select('SUM(CASE WHEN tagihan.`status` = "belum" then 1 ELSE 0 END) AS jumlah_belum ')
		->join('mahasiswa_periode', 'tagihan.mahasiswa_periode_id = mahasiswa_periode.id')
		->join('mahasiswa', 'mahasiswa.id = mahasiswa_periode.mahasiswa_id')
		->where('mahasiswa.id', $mahasiswa_id)
		->get()
		->getRow();
	}
}
