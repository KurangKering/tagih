<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\FakultasModel;
use App\Models\ProdiModel;
class FakultasProdiSeeder extends Seeder
{
	public function run()
	{
		$dataFakultas = [

			[
				"nama" => "Fakultas Ekonomi"
			],
			[
				"nama" => "Fakultas Hukum"
			],
			[
				"nama" => "Fakultas Teknik"
			],

		];

		$dataProdi = [
			[
				[
					"nama" => "Jurusan Ilmu Ekonomi",
				],
				[
					"nama" => "Jurusan Akuntansi",
				],
				[
					"nama" => "Jurusan Manajemen",
				],
			],
			[
				[
					"nama" => "Jurusan Ilmu Hukum",
				],
			],
			[
				[
					"nama" => "Jurusan Teknik Sipil",
				],
				[
					"nama" => "Jurusan Teknik Elektro",
				],
				[
					"nama" => "Jurusan Teknik Mesin",
				],
				[
					"nama" => "Jurusan Teknik Metalurgi dan Material",
				],
				[
					"nama" => "Jurusan Teknik Kimia",
				],
				[
					"nama" => "Jurusan Teknik Industri",
				],
				[
					"nama" => "Jurusan Teknik Kelautan",
				],
				[
					"nama" => "Jurusan Teknik Lingkungan",
				],
				[
					"nama" => "Jurusan Teknik Fisika",
				],
				[
					"nama" => "Jurusan Teknik Geodesi",
				],
				[
					"nama" => "Jurusan Teknik Informatika",
				],
			],

		];

		for ($i=0; $i < count($dataFakultas); $i++) { 

			$fakultasModel = new FakultasModel();
			$fakultasModel->insert($dataFakultas[$i]);
			$idFakultas = $fakultasModel->getInsertID();

			for ($j=0; $j < count($dataProdi[$i]); $j++) { 
				$prodiModel = new ProdiModel();
				$dataProdi[$i][$j]['fakultas_id'] = $idFakultas;
				$prodiModel->insert($dataProdi[$i][$j]);
			}
		}
	}
}
