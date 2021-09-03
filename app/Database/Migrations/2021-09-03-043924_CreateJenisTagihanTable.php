<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJenisTagihanTable extends Migration
{
	const TABLE = 'jenis_tagihan';

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
			'description'       => [
				'type'       => 'VARCHAR',
				'constraint' => '50',
				'null'	=> true,
			],
			'biaya'       => [
				'type'       => 'INT',
				'constraint' => '11',
				'null'	=> false,
			],
		]);
		$this->forge->addKey('id', true);

		$this->forge->createTable(CreateJenisTagihanTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreateJenisTagihanTable::TABLE);
	}

}
