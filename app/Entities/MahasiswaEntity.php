<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\ProdiModel;
use App\Models\MahasiswaPeriodeModel;

class MahasiswaEntity extends Entity
{
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];


	public function getProdi()
	{
		$prodiModel = new ProdiModel();
		return $prodiModel->where('id', $this->prodi_id);
	}

	public function getMahasiswaPeriode()
	{
		$mahasiswaPeriodeModel = new MahasiswaPeriodeModel();
		return $mahasiswaPeriodeModel->where('mahasiswa_id', $this->id);
	}
}

