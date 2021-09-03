<?php

namespace App\Controllers;

class BendaharaController extends BaseController
{
    public function index()
    {
    }

    public function daftarTagihan()
    {
        return view('bendahara/tagihan/index');
    }
}
