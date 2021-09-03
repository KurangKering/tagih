<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProdiTable extends Migration
{
	const TABLE = 'prodi';

	public function up()
	{
		$this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'fakultas_id'          => [
				'type'           => 'INT',
				'unsigned'	=> true,
				'constraint'     => 11,
			],
			'nama'       => [
				'type'       => 'VARCHAR',
				'constraint' => '200',
				'null'	=> false,
			],
		]);
		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('fakultas_id', 'fakultas', 'id', '', 'CASCADE');

		$this->forge->createTable(CreateProdiTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreateProdiTable::TABLE);
	}
}
