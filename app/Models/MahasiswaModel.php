<?php

namespace App\Models;

use CodeIgniter\Model;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\Codeigniter4Adapter;

class MahasiswaModel extends Model
{
	protected $table                = 'mahasiswa';
	protected $allowedFields        = [
		'nim',
		'nama',
		'semester_berjalan',
		'tahun_masuk',
		'no_hp',
		'alamat',
		'prodi_id',
		'status',
	];
	protected $returnType    = 'App\Entities\MahasiswaEntity';



	public function getDataJsonDT()
	{
		$dt = new Datatables(new Codeigniter4Adapter());
		$dt->query('select mahasiswa.id, 
			nim, 
			mahasiswa.nama, 
			semester_berjalan, 
			no_hp, 
			tahun_masuk, 
			status, 
			prodi.nama as program_studi 
			from mahasiswa 
			join prodi on mahasiswa.prodi_id = prodi.id
			');

		$dt->add('action', function($q) {
			return '';
		});

		echo $dt->generate();
	}


	public function getMahasiswaByPeriode($periode)
	{
		return $this
		->select('mahasiswa.*')
		->join('mahasiswa_periode', 'mahasiswa.id = mahasiswa_periode.mahasiswa_id')
		->join('periode', 'mahasiswa_periode.periode_id = periode.id')
		->where('periode.id', $periode)
		->findAll();
	}

}

