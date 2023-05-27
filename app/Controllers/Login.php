<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoginModel;
use App\Models\UsersModel;
use CodeIgniter\Config\View;

class Login extends BaseController
{
    protected $LoginModel;
    public function __construct()
    {
        $this->LoginModel = new LoginModel();
    }
    public function index()
    {
        return view('login/index');
    }

    public function cekUser()
    {
        $nameuser = $this->request->getPost('nameuser');
        $pass = $this->request->getPost('pass');

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'nameuser' => [
                'rules' => 'required',
                'label' => 'username',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'pass' => [
                'rules' => 'required',
                'label' => 'password',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errnameuser' => $validation->getError('nameuser'),
                'errPass' => $validation->getError('pass')
            ];
            session()->setFlashdata($pesan);
            return redirect()->to(site_url('login/index'));
        } else {
            $cekUserLogin = $this->LoginModel->find($nameuser);

            if ($cekUserLogin == null) {
                $pesan = [
                    'errnameuser' => 'Maaf user tidak terdaftar'
                ];

                session()->setFlashdata($pesan);
                return redirect()->to(site_url('login/index'));
            } else {

                $passwordUser = $cekUserLogin['userpassword'];

                if (password_verify($pass, $passwordUser)) {
                    $simpan_session = [
                        'nameuser' => $nameuser,
                        'namauser' => $cekUserLogin['namalengkap']
                    ];
                    session()->set($simpan_session);

                    return redirect()->to('/main/index');
                } else {
                    $pesan = [
                        'errPass' => 'Maaf password anda salah'
                    ];
                    session()->setFlashdata($pesan);
                    return redirect()->to(site_url('login/index'));
                }
            }
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login/index');
    }
}
