<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMahasiswaTable extends Migration
{
	const TABLE = 'mahasiswa';

	public function up()
	{
		$this->forge->addField([

			'id' =>	[
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'nim' =>	[
				'type'			=> 'CHAR',
				'constraint'	=> 15,
				'unique'		=> true,
			],
			'nama' =>	[
				'type'			=> 'VARCHAR',
				'constraint'	=> 200,
				'null'			=> false,
			],
			'semester_berjalan' =>	[
				'type'			=> 'TINYINT',
				'constraint'	=> 4,
				'null'			=> true,
			],
			'tahun_masuk' =>	[
				'type'			=> 'DATE',
				'null'			=> true,
			],
			'no_hp' =>	[
				'type'			=> 'VARCHAR',
				'constraint'	=> 15,
				'null'			=> true,
			],

			'email' =>	[
				'type'			=> 'VARCHAR',
				'constraint'	=> 50,
				'null'			=> true,
			],
			'alamat' =>	[
				'type'			=> 'TINYTEXT',
				'null'			=> true,
			],
			'prodi_id' =>	[
				'type'			=> 'INT',
				'constraint'	=> 11,
				'unsigned'		=> true,
			],
			'status' =>	[
				'type'			=> 'ENUM',
				'constraint'	=> ['active', 'inactive'],
				'null'			=> false,
				'default'		=> 'active',
			],
		]);



		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('prodi_id', 'prodi', 'id');

		$this->forge->createTable(CreateMahasiswaTable::TABLE);
	}

	public function down()
	{
		$this->forge->dropTable(CreateMahasiswaTable::TABLE);
	}


}
