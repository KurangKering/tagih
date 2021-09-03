<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRiwayatBayarTable extends Migration
{

	const TABLE = 'riwayat_bayar';

	public function up()
	{
		$this->forge->addField([

			'id' =>	[
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'tagihan_id' =>	[
				'type'			=> 'INT',
				'constraint'	=> 11,
				'unsigned'       => true,
				'null'			=> false,
			],
			'mahasiswa_payment_id' =>	[
				'type'			=> 'INT',
				'constraint'	=> 11,
				'unsigned'       => true,
				'null'			=> false,
			],
			'waktu_bayar' =>	[
				'type'			=> 'DATETIME',
				'null'			=> false,
			],
			

		]);


		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('tagihan_id', 'tagihan', 'id');
		$this->forge->addForeignKey('mahasiswa_payment_id', 'mahasiswa_payment', 'id');

		$this->forge->createTable(CreateRiwayatBayarTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreateRiwayatBayarTable::TABLE);
	}



}
