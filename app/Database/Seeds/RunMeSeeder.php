<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RunMeSeeder extends Seeder
{
	public function run()
	{
		$this->call('FakultasProdiSeeder');
		$this->call('JenisTagihanSeeder');
		$this->call('PaymentSeeder');
		$this->call('RoleWithUserExceptMahasiswaSeeder');
		$this->call('UserWithMahasiswaSeeder');
		$this->call('MahasiswaPaymentSeeder');
	}
}
