<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatBayarModel extends Model
{
	protected $table                = 'riwayat_bayar';
	protected $allowedFields        = [
		'tagihan_id',
		'mahasiswa_payment_id',
		'waktu_bayar',
	];

	protected $returnType    = 'App\Entities\RiwayatBayarEntity';


}
