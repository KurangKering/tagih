<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class BendaharaController extends BaseController
{
    public function index()
    {
        //
    }

    public function daftarTagihan()
    {
        return view('bendahara/tagihan/index');
    }
}
