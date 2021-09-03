<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisTagihanModel extends Model
{
    protected $table = 'jenis_tagihan';

    protected $allowedFields = [
        'nama',
        'description',
        'biaya',
    ];
}
