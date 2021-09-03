<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoleTable extends Migration
{
	const TABLE = 'role';

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
				'constraint' => 50,
				'null'	=> false,
			],
			'description'       => [
				'type'       => 'VARCHAR',
				'constraint'	=> 200,
				'null'	=> true,
			],
			'model'       => [
				'type'       => 'VARCHAR',
				'constraint' => 200,
				'null'	=> true,
			],
		]);
		$this->forge->addKey('id', true);

		$this->forge->createTable(CreateRoleTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreateRoleTable::TABLE);
	}


}
