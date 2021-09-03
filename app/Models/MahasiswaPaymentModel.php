<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaPaymentModel extends Model
{
	protected $table                = 'mahasiswa_payment';
	protected $allowedFields        = [
		'mahasiswa_id',
		'payment_id',
		'number',
	];


	public function getFromTagihan($filters)
	{
		$sql = "select mahasiswa_payment.id as mahasiswa_payment_id,
		mahasiswa_payment.number,
		jenis_tagihan.nama as jenis_tagihan,
		mahasiswa.nama as nama_mahasiswa,
		mahasiswa_payment.payment_id,
		tagihan.id as tagihan_id
		from tagihan 
		join jenis_tagihan on tagihan.jenis_tagihan_id = jenis_tagihan.id
		join mahasiswa_periode mp on tagihan.mahasiswa_periode_id = mp.id
		join mahasiswa on mahasiswa.id = mp.mahasiswa_id
		join mahasiswa_payment on mahasiswa.id = mahasiswa_payment.mahasiswa_id
		where tagihan.id = ? and mahasiswa_payment.payment_id = ?
		";

		$db = \Config\Database::connect();
		$query = $db->query($sql, [$filters['tagihan_id'], $filters['via']]);

		return $query->getRow();
	}
}
