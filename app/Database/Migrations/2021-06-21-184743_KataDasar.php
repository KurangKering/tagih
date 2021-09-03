<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KataDasar extends Migration
{	
	const TABLE = 'kata_dasar';

	public function up()
	{
		$this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'constraint'     => 5,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'kata'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
			],
			'arti_kata'       => [
				'type'       => 'VARCHAR',
				'constraint' => '250',
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable(KataDasar::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(KataDasar::TABLE);
	}

}