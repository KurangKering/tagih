<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\MahasiswaPeriodeModel;

class PeriodeEntity extends Entity
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
		return $mahasiswaPeriodeModel->where('periode_id', $this->id);
	}



}
