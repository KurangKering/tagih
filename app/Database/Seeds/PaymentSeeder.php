<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\PaymentModel;

class PaymentSeeder extends Seeder
{
	public function run()
	{
		$dataPayment = [
			[
				"nama" => "OVO",
				"va_depan" => "001"
			],
			[
				"nama" => "DANA",
				"va_depan" => "002"
			],
		];

		(new PaymentModel())->insertBatch($dataPayment);
	}
}
