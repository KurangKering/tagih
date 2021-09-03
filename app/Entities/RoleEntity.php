<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\UserModel;

class RoleEntity extends Entity
{
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];

	public function getUser()
	{
		$userModel = new UserModel();
		return $userModel->where('role_id', $this->id);
	}
}
