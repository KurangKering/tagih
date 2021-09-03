<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\TagihanModel;
use App\Models\MahasiswaPaymentModel;

class RiwayatBayarEntity extends Entity
{
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];


	public function getTagihan()
	{
		$tagihanModel = new TagihanModel();
		return $tagihanModel->where('id', $this->tagihan_id);
	}

	public function getMahasiswaPayment()
	{
		$mahasiswaPaymentModel = new MahasiswaPaymentModel();
		return $mahasiswaPaymentModel->where('id', $this->mahasiswa_payment_id);
	}
}
