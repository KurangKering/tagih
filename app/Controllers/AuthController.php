<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\UserModel;

class AuthController extends BaseController
{
    /**
     * menu login.
     * menampilkan halaman login.
     */
    public function login()
    {
        return view('login');
    }

    /**
     * proses login sederhana.
     *
     * @return json sukses atau gagal login
     */
    public function loginProcess()
    {
        // buat instance object 
        $userModel = new UserModel();
        // tampung data username
        $username = $this->request->getPost('username') ?? '1';
        // tampung data password
        $password = $this->request->getPost('password') ?? '1';
        // cari user berdasarkan username dan password
        $userData = $userModel->where(['username' => $username, 'password' => $password])
        ->first();


        $response = [];
        $this->session->set('authenticated', false);
        //jika data user ditemukan
        if ($userData) {
            // set session data
            $this->session->set('authenticated', true);
            $this->session->set('user_id', $userData->id);
            $this->session->set('username', $userData->username);
            $role = $userData->getRole()->first()->nama;
            $this->session->set('role', $role);

            // jika yang login adalah role mahasiswa
            if ('mahasiswa' == $role) {
                $mahasiswaModel = new MahasiswaModel();
                //jika data mahasiswa ditemukan berdasarkan id user
                $mahasiswaData = $mahasiswaModel->where('user_id', $userData->id)->first();
                $sessionMahasiswa = [
                    'mahasiswa_id' => $mahasiswaData->id,
                    'mahasiswa_nama' => $mahasiswaData->nama,
                ];
                // atur session data berisi data mahasiswa
                $this->session->set('data', $sessionMahasiswa);
            }
            $response['success'] = true;
        } else {
            $response['success'] = false;
        }

        return $this->response->setJSON($response);
    }

    /**
     * proses logout.
     */
    public function logoutProcess()
    {
        // hilangkan semua sesion
        $this->session->destroy();
        // redirect ke halaman login
        return redirect()->to('auth/login');
    }
}
