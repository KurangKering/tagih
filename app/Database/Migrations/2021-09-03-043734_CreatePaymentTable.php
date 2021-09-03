<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentTable extends Migration
{
	const TABLE = 'payment';

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
			'va_depan'       => [
				'type'       => 'VARCHAR',
				'constraint' => '10',
				'null'	=> false,
			],
		]);
		$this->forge->addKey('id', true);

		$this->forge->createTable(CreatePaymentTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreatePaymentTable::TABLE);
	}
}
