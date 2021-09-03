<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\MahasiswaModel;
use App\Models\ProdiModel;

class UserWithMahasiswaSeeder extends Seeder
{
	public function run()
	{
		$jumlahData = 100;
		$faker = \Faker\Factory::create('id_ID');

		$mahasiswaRoleID = (new RoleModel())->where('nama', 'mahasiswa')->get()->getRow();
		if ($mahasiswaRoleID == null) {
			echo 'tidak ada role mahasiswa';
			return false;
		}
		
		$prodiId = (new ProdiModel())->findColumn('id');
		$passwordDefault = "asd";

		for ($i=0; $i < $jumlahData; $i++) { 
			$nim = (string) $faker->unique()->randomNumber($nbDigits = 5);
			$nim .= (string) $faker->unique()->randomNumber($nbDigits = 5);


			$user = [
				'username' => $nim,
				'password' => $passwordDefault,
				'role_id'  => $mahasiswaRoleID->id,
			];

			$userModel = new UserModel();
			$userModel->insert($user);
			$noDepan = ['0812', '0813', '0878', '0831', '0852'];
			$noTengah = $faker->randomNumber($nbDigits = 4);
			$noBelakang = $faker->randomNumber($nbDigits = 4);
			$noHp = $noDepan[array_rand($noDepan)] . $noTengah . $noBelakang;
			$mahasiswa = [
				'nim' => $nim,
				'nama' => $faker->name,
				'tahun_masuk' => date('Y-m-d'),
				'no_hp'	=> (string) $noHp,
				'email' => $faker->email,
				'alamat' => $faker->address,
				'prodi_id' => $prodiId[array_rand($prodiId)],
				'status'	=> 'active',
				'user_id'	=> $userModel->getInsertID(),

			];
			$mahasiswaModel = new MahasiswaModel();
			$mahasiswaModel->insert($mahasiswa);

			if ($i == 0 && $mahasiswaModel->getInsertID()) {
				echo "Username : {$user['username']}" . PHP_EOL;
				echo "Password : {$passwordDefault}" . PHP_EOL;
				echo "Role : Mahasiswa" . PHP_EOL;
				echo PHP_EOL;
			} 
		}
	}
}
