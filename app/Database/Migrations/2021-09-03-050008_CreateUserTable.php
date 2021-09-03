<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTable extends Migration
{
	const TABLE = 'user';

	public function up()
	{
		$this->forge->addField([

			'id' =>	[
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'username' =>	[
				'type'			=> 'VARCHAR',
				'constraint'	=> 50,
				'unique'		=> true,
			],
			'password' =>	[
				'type'			=> 'VARCHAR',
				'constraint'	=> 50,
				'null'			=> false,
			],
			'role_id' =>	[
				'type'			=> 'INT',
				'constraint'	=> 11,
				'unsigned'		=> true,
				'null'			=> false,
			],
			'foreign_id' =>	[
				'type'			=> 'INT',
				'constraint'	=> 11,
				'unsigned'		=> true,
				'null'			=> true,
			],
		
		]);




		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('role_id', 'role', 'id');

		$this->forge->createTable(CreateUserTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreateUserTable::TABLE);
	}


}
