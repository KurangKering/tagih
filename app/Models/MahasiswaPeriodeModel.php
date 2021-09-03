<?php

namespace App\Models;

use CodeIgniter\Model;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\Codeigniter4Adapter;

class MahasiswaPeriodeModel extends Model
{
    protected $table = 'mahasiswa_periode';

    protected $allowedFields = [
        'mahasiswa_id',
        'periode_id',
        'semester',
        'status',
    ];

    protected $returnType = 'App\Entities\MahasiswaPeriodeEntity';

    public function getDataJsonDT($filters = null)
    {
        $dt = new Datatables(new Codeigniter4Adapter());
        $query = 'select 
		mahasiswa_periode.id,
		periode.id as periode_id,
		mahasiswa.nim, 
		mahasiswa.nama, 
		mahasiswa.semester_berjalan, 
		periode.periode,
		periode.tahun
		from mahasiswa 
		join mahasiswa_periode on mahasiswa.id = mahasiswa_periode.mahasiswa_id
		join periode on mahasiswa_periode.periode_id = periode.id
		';

        $filter_array = [];

        if ($filters) {
            $this->cleaningFilters($filters);

            if ($this->checkFilters($filters, 'periode_id')) {
                $filter_array['periode_id'] = $filters['periode_id'];
            }

            if ($this->checkFilters($filters, 'semester')) {
                $filter_array['mahasiswa_periode.semester'] = $filters['semester'];
            }
        }

        $where = [];
        foreach ($filter_array as $key => $value) {
            $where[] = $key.' = '.$value;
        }

        if (!empty($where)) {
            $query .= ' where '.implode(' AND ', $where);
        }

        $dt->query($query);

        $dt->add(
            'action',
            function ($q) {
                return '';
            }
        );
        echo $dt->generate();
    }

    private function checkFilters($array, $key)
    {
        return isset($array[$key]) && '' != $array[$key];
    }

    private function cleaningFilters(&$array)
    {
        $array['periode_id'] = '' != $array['periode_id'] ? (int) $array['periode_id'] : '';
        $array['semester'] = '' != $array['semester'] ? (int) $array['semester'] : '';
    }

    public function getSemesterDistinct()
    {
        return $this->select('semester')->distinct()->orderBy('semester', 'ASC')->get()->getResult();
    }
}
