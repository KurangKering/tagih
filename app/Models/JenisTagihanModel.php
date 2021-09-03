<?php

namespace App\Models;

use CodeIgniter\Model;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\Codeigniter4Adapter;
class JenisTagihanModel extends Model
{
	protected $table                = 'jenis_tagihan';
	protected $allowedFields        = [
		'nama',
		'description',
		'biaya',
	];

}
