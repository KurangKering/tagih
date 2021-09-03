<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\JenisTagihanModel;

class JenisTagihanSeeder extends Seeder
{
	public function run()
	{
		$dataJenisTagihan = [
			[
				"nama"	=> "SPP",
				"description"	=> "Pembayaran SPP",
				"biaya" => 1000000,
			],
			[
				"nama"	=> "Pembangunan",
				"description"	=> "Pembayaran Uang Pembangunan",
				"biaya" => 200000,
			],
			
		];

		(new JenisTagihanModel())->insertBatch($dataJenisTagihan);
	}
}
