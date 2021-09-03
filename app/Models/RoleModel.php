<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'role';

    protected $allowedFields = [
        'nama',
        'description',
        'model',
    ];

    protected $returnType = 'App\Entities\RoleEntity';
}
