<?php

namespace App\Models;

use CodeIgniter\Model;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\Codeigniter4Adapter;

class PeriodeModel extends Model
{
	protected $table                = 'periode';
	protected $allowedFields        = [
		'periode',
		'tahun',
		'waktu_mulai'
	];
	protected $returnType    = 'App\Entities\PeriodeEntity';

	

	public function getDataToJSON()
	{
		$dt = new Datatables(new Codeigniter4Adapter());
		$dt->query('
			select p.id, 
			p.periode, 
			p.tahun, 
			count(mp.id) as total_mahasiswa from periode p 
			join mahasiswa_periode mp on p.id = mp.periode_id
			group by p.id
			');

		$dt->add('action', function($q) {
			return '';
		});

		$dt->edit('total_mahasiswa', function($q) {
			return $q['total_mahasiswa'] . " Mahasiswa";
		});
		echo $dt->generate();
	}

}
