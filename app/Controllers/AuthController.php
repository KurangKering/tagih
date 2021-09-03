<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\MahasiswaModel;

class AuthController extends BaseController
{

    public function index()
    {
    }

    public function login()
    {
        return view('login');
    }


    public function loginProcess()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username') ?? '1';
        $password = $this->request->getPost('password') ?? '1';
        $userData = $userModel->where(array('username' => $username, 'password' => $password))
        ->first();

        $response = array();
        $this->session->set('authenticated', false);

        if ($userData) {
            $this->session->set('authenticated', true);
            $this->session->set('user_id', $userData->id);
            $this->session->set('username', $userData->username);
            $role = $userData->getRole()->first()->nama;
            $this->session->set('role', $role);

            if ($role == 'mahasiswa') {
                $mahasiswaModel = new MahasiswaModel();
                $mahasiswaData = $mahasiswaModel->where('id', $userData->foreign_id)->first();
                $sessionMahasiswa = array(
                    'mahasiswa_id' => $mahasiswaData->id,
                    'mahasiswa_nama' => $mahasiswaData->nama,
                );
                $this->session->set('data', $sessionMahasiswa);
            }
            $response['success'] = true;
        } else {
            $response['success'] = false;
        }

        return $this->response->setJSON($response);
    }

    public function logoutProcess()
    {
        $this->session->destroy();

        return redirect()->to('auth/login');
    }
}
