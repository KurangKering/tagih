<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\FakultasModel;
use App\Models\MahasiswaModel;
class ProdiEntity extends Entity
{
	protected $datamap = [];
	protected $casts   = [];

	public function getFakultas()
	{
		$fakultasModel = new FakultasModel();
		return $fakultasModel->where('id', $this->fakultas_id);
	}


	public function getMahasiswa()
	{
		$mahasiswaModel = new MahasiswaModel();
		return $mahasiswaModel->where('prodi_id', $this->id);
	}
}
