<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTagihanTable extends Migration
{

	const TABLE = 'tagihan';

	public function up()
	{
		$this->forge->addField([

			'id' =>	[
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'mahasiswa_periode_id' =>	[
				'type'			=> 'INT',
				'constraint'	=> 11,
				'unsigned'       => true,
				'null'			=> false,
			],
			'jenis_tagihan_id' =>	[
				'type'			=> 'INT',
				'constraint'	=> 11,
				'unsigned'       => true,
				'null'			=> false,
			],
			'biaya' =>	[
				'type'			=> 'INT',
				'constraint'	=> 11,
				'unsigned'       => true,
				'null'			=> false,
			],
			'status' =>	[
				'type'			=> 'ENUM',
				'constraint'	=> ['lunas', 'belum'],
				'null'			=> false,
				'default'		=> 'belum',
			],
			

		]);

		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('mahasiswa_periode_id', 'mahasiswa_periode', 'id');
		$this->forge->addForeignKey('jenis_tagihan_id', 'jenis_tagihan', 'id');
		$this->forge->addKey(['mahasiswa_periode_id', 'jenis_tagihan_id'], false, true);

		$this->forge->createTable(CreateTagihanTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreateTagihanTable::TABLE);
	}



}
