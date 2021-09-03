<?php 


use App\Models\MahasiswaModel;
use CodeIgniter\Test\CIUnitTestCase;
class ModelTest extends CIUnitTestCase
{

	public function testModelbalbal()
	{
		$model = new MahasiswaModel();
		$dataSeluruhMahasiswa = (new MahasiswaModel())->findAll();
		var_dump (array_column($dataSeluruhMahasiswa, 'id'));
		die();
	}
}