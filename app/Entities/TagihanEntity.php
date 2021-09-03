<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\MahasiswaPeriodeModel;
use App\Models\JenisTagihanModel;

class TagihanEntity extends Entity
{
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];


	public function getMahasiswaPeriode()
	{
		$mahasiswaPeriodeModel = new MahasiswaPeriodeModel();
		return $mahasiswaPeriodeModel->where('id', $this->mahasiswa_periode_id);
	}

	public function getJenisTagihan()
	{
		$jenisTagihanModel = new JenisTagihanModel();
		return $jenisTagihanModel->where('id', $this->jenis_tagihan_id);
	}
}
