<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\MahasiswaModel;
use App\Models\MahasiswaPaymentModel;
use App\Models\PaymentModel;


class MahasiswaPaymentSeeder extends Seeder
{
	public function run()
	{
		$dataPayments = (new PaymentModel())->get()->getResult();
		$dataMahasiswa = (new MahasiswaModel())->get()->getResult();

		for ($i=0; $i < count($dataMahasiswa); $i++) { 
			for ($j=0; $j < count($dataPayments); $j++) { 
				
				$mahasiswaPayment = [
					'mahasiswa_id' => $dataMahasiswa[$i]->id,
					'payment_id' => $dataPayments[$j]->id,
					'number' => $dataPayments[$j]->va_depan . $dataMahasiswa[$i]->nim,
				];
				$mahasiswaPaymentModel = new MahasiswaPaymentModel();
				$mahasiswaPaymentModel->insert($mahasiswaPayment);

			}
		}
	}
}
