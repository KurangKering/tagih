<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeriodeTable extends Migration
{
	const TABLE = 'periode';

	public function up()
	{
		$this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'periode'       => [
				'type'       => 'ENUM',
				'constraint' => ['ganjil', 'genap'],
				'null'	=> false,
			],
			'tahun'       => [
				'type'       => 'YEAR',
				'null'	=> false,
			],
			'waktu_mulai'       => [
				'type'       => 'DATETIME',
			],
		]);
		$this->forge->addKey('id', true);

		$this->forge->createTable(CreatePeriodeTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreatePeriodeTable::TABLE);
	}

}
