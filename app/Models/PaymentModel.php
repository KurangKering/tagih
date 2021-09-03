<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payment';

    protected $allowedFields = [
        'nama',
        'va_depan',
    ];

    protected $returnType = 'App\Entities\PaymentEntity';
}
