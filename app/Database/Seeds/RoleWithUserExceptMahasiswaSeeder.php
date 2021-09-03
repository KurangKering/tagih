<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\RoleModel;
use App\Models\UserModel;

class RoleWithUserExceptMahasiswaSeeder extends Seeder
{
	public function run()
	{
		$dataRole = [
			[
				"nama"	=> "pimpinan",
				"description"	=> "Hak akses pimpinan",

			],
			[
				"nama"	=> "bendahara",
				"description"	=> "Hak akses bendahara",

			],
			[
				"nama"	=> "mahasiswa",
				"description"	=> "Hak akses mahasiswa",
			],
		];
		echo PHP_EOL;
		echo PHP_EOL;

		for ($i=0; $i < count($dataRole); $i++) { 
			$roleModel = new RoleModel();
			$roleModel->insert($dataRole[$i]);

			if ($dataRole[$i]['nama'] == "pimpinan") {
				$username = 'pimpinan';
				$password = 'asd';

				$userModel = new UserModel();
				$userModel->insert(
					[
						'username' => $username,
						'password'	=> $password,
						'role_id'	=> $roleModel->getInsertID(),
					]
				);
				if ($userModel->getInsertID()) {
					echo "Username : {$username}" . PHP_EOL;
					echo "Password : {$password}" . PHP_EOL;
					echo "Role : Pimpinan" . PHP_EOL;
					echo PHP_EOL;
				}
			} elseif ($dataRole[$i]['nama'] == "bendahara") {
				$username = "bendahara";
				$password = "asd";

				$userModel = new UserModel();
				$userModel->insert(
					[
						'username' => $username,
						'password'	=> $password,
						'role_id'	=> $roleModel->getInsertID(),
					]
				);
				if ($userModel->getInsertID()) {
					echo "Username : {$username}" . PHP_EOL;
					echo "Password : {$password}" . PHP_EOL;
					echo "Role : Bendahara" . PHP_EOL;
					echo PHP_EOL;


				}
			}
		}


	}
}
