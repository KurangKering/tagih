<?php

namespace App\Models;

use CodeIgniter\Model;

class FakultasModel extends Model
{
	protected $table                = 'fakultas';
	protected $allowedFields        = [
		'nama',
	];
	protected $returnType    = 'App\Entities\FakultasEntity';

}
