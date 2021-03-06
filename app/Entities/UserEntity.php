<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\RoleModel;

class UserEntity extends Entity
{
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];


	public function getRole()
	{
		$roleModel = new RoleModel();
		return $roleModel->where('id', $this->role_id);
	}
}
