<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFakultasTable extends Migration
{
	const TABLE = 'fakultas';

	public function up()
	{
		$this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'nama'       => [
				'type'       => 'VARCHAR',
				'constraint' => '50',
				'null'	=> false,
			],
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable(CreateFakultasTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreateFakultasTable::TABLE);
	}
}
