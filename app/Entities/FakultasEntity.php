<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\ProdiModel;

class FakultasEntity extends Entity
{
	protected $datamap = [];
	protected $casts   = [];

	public function getProdi()
	{
		$prodiModel = new ProdiModel();
		return $prodiModel->where('fakultas_id', $this->id);
	}
}
