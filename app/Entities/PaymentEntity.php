<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\MahasiswaPaymentModel;

class PaymentEntity extends Entity
{
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];


	public function getMahasiswaPayment()
	{
		$mahasiswaPaymentModel = new MahasiswaPaymentModel();
		return $mahasiswaPaymentModel->where('payment_id', $this->id);
	}
}
