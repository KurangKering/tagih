<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMahasiswaPeriodeTable extends Migration
{

	const TABLE = 'mahasiswa_periode';

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
			'periode_id' =>	[
				'type'			=> 'INT',
				'constraint'	=> 11,
				'unsigned'       => true,
				'null'			=> false,
			],
			'semester' =>	[
				'type'			=> 'TINYINT',
				'constraint'	=> 4,
				'null'			=> false,
			],
			'status' =>	[
				'type'			=> 'ENUM',
				'constraint'	=> ['active', 'inactive'],
				'null'			=> false,
				'default'		=> 'active',
			],

		]);

		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('mahasiswa_id', 'mahasiswa', 'id');
		$this->forge->addForeignKey('periode_id', 'periode', 'id');

		$this->forge->createTable(CreateMahasiswaPeriodeTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreateMahasiswaPeriodeTable::TABLE);
	}



}
