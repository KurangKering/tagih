<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMahasiswaPaymentTable extends Migration
{

	const TABLE = 'mahasiswa_payment';

	public function up()
	{
		$this->forge->addField([

			'id' =>	[
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'mahasiswa_id' =>	[
				'type'			=> 'INT',
				'constraint'	=> 11,
				'unsigned'       => true,
				'null'		=> false,
			],
			'payment_id' =>	[
				'type'			=> 'INT',
				'constraint'	=> 11,
				'unsigned'       => true,
				'null'			=> false,
			],
			'number' =>	[
				'type'			=> 'VARCHAR',
				'constraint'	=> 50,
				'null'			=> false,
			],

		]);


		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('mahasiswa_id', 'mahasiswa', 'id');
		$this->forge->addForeignKey('payment_id', 'payment', 'id');
		$this->forge->addKey(array('mahasiswa_id', 'payment_id'), false, true);

		$this->forge->createTable(CreateMahasiswaPaymentTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreateMahasiswaPaymentTable::TABLE);
	}



}
