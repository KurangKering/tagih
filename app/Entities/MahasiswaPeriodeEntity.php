<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\MahasiswaModel;
use App\Models\PeriodeModel;

class MahasiswaPeriodeEntity extends Entity
{
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];

	public function getMahasiswa()
	{
		$mahasiswaModel = new MahasiswaModel();
		return $mahasiswaModel->where('id', $this->mahasiswa_id);
	}

	public function getPeriode()
	{
		$periodeModel = new PeriodeModel();
		return $periodeModel->where('id', $this->periode_id);
	}
}
