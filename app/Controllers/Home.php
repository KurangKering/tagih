<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $role = session()->get('role');

        switch ($role) {
        	case 'pimpinan':
        		return redirect()->to('tagihan/data-pimpinan');
        		break;
        	case 'bendahara':
        		return redirect()->to('tagihan/data-bendahara');
        		break;
			
			case 'mahasiswa':
        		return redirect()->to('tagihan/data-mahasiswa');
        		break;
        	
        	default:
        		break;
        }
    }
}
