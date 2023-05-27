<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use \Hermawan\DataTables\DataTable;

class Users extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Manajemen user'
        ];
        return view('users/data', $data);
    }

    public function listData()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $builder = $db->table('users')
                ->select('username, namalengkap, levelnama, useraktif, userlevelid')
                ->join('levels', 'levelid=userlevelid');

            return DataTable::of($builder)
                ->addNumbering('nomor')
                ->add('status', function ($row) {
                    if ($row->useraktif == '1') {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-danger">Non Active</span>';
                    }
                })
                ->add('aksi', function ($row) {
                    if ($row) {
                        return "<button type=\"button\" class=\"btn btn-info btn-sm\" onclick=\"view('" . $row->username . "')\"> View </button>";
                    } else {
                    }
                })
                ->toJson(true);
        }
    }

    public function formTambah()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();

            $data = [
                'datalevel' => $db->table('levels')->get()
            ];
            echo view('users/modaltambah', $data);
        }
    }

    public function simpan()
    {
        if ($this->request->isAJAX()) {
            $nameuser = $this->request->getPost('nameuser');
            $namalengkap = $this->request->getPost('namalengkap');
            $level = $this->request->getPost('level');

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'nameuser' => [
                    'rules' => 'required|is_unique[users.username]',
                    'label' => 'id user',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'is_unique' => '{field} tidak boleh ada yang sama'
                    ]
                ],
                'namalengkap' => [
                    'rules' => 'required',
                    'label' => 'nama lengkap',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'level' => [
                    'rules' => 'required',
                    'label' => 'level',
                    'errors' => [
                        'required' => '{field} wajib dipilih'
                    ]
                ]
            ]);

            if (!$valid) {
                $error = [
                    'nameuser' => $validation->getError('nameuser'),
                    'namalengkap' => $validation->getError('namalengkap'),
                    'level' => $validation->getError('level')
                ];

                $json = [
                    'error' => $error
                ];
            } else {
                $modelUser = new UsersModel();
                $modelUser->insert([
                    'username' => $nameuser,
                    'namalengkap' => $namalengkap,
                    'userlevelid' => $level
                ]);

                $json = [
                    'sukses' => 'Simpan data user berhasil !'
                ];
            }
            echo json_encode($json);
        }
    }

    public function formedit()
    {
        if ($this->request->isAJAX()) {
            $nameuser = $this->request->getPost('username');
            $modelUser = new UsersModel();
            $rowUser = $modelUser->find($nameuser);

            if ($rowUser) {
                $db = \Config\Database::connect();

                $data = [
                    'datalevel' => $db->table('levels')->get(),
                    'nameuser' => $nameuser,
                    'namalengkap' => $rowUser['namalengkap'],
                    'level' => $rowUser['userlevelid'],
                    'status' => $rowUser['useraktif']
                ];
                echo view('users/modaledit', $data);
            }
        }
    }

    public function update()
    {
        if ($this->request->isAJAX()) {
            $nameuser = $this->request->getPost('nameuser');
            $namalengkap = $this->request->getPost('namalengkap');
            $level = $this->request->getPost('level');


            $modelUser = new UsersModel();
            $modelUser->update($nameuser, [
                'username' => $nameuser,
                'namalengkap' => $namalengkap,
                'userlevelid' => $level
            ]);

            $json = [
                'sukses' => 'Update data user berhasil !'
            ];
        }
        echo json_encode($json);
    }

    public function updateStatus()
    {
        if ($this->request->isAJAX()) {
            $nameuser = $this->request->getPost('nameuser');
            $modelUser = new UsersModel();
            $rowUser = $modelUser->find($nameuser);

            $useraktif = $rowUser['useraktif'];
            if ($useraktif == '1') {
                $modelUser->update($nameuser, [
                    'useraktif' => '0'
                ]);
            } else {
                $modelUser->update($nameuser, [
                    'useraktif' => '1'
                ]);
            }

            $json = [
                'sukses' => ''
            ];
            echo json_encode($json);
        }
    }

    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $nameuser = $this->request->getPost('nameuser');
            $modelUser = new UsersModel();
            $modelUser->delete($nameuser);

            $json = [
                'sukses' => 'Id user berhasil dihapus!'
            ];
            echo json_encode($json);
        }
    }

    public function resetpassword()
    {
        if ($this->request->isAJAX()) {
            $nameuser = $this->request->getPost('nameuser');
            $modelUser = new UsersModel();

            $passRandom = rand(1, 99999);
            $passHashBaru = password_hash($passRandom, PASSWORD_DEFAULT);

            $modelUser->update($nameuser, [
                'userpassword' => $passHashBaru
            ]);

            $json = [
                'sukses' => '',
                'passwordBaru' => $passRandom
            ];
            echo json_encode($json);
        }
    }

    public function gantiPassword()
    {
        $data = [
            'title' => 'Ganti Password'
        ];
        return view('login/formresetpassword', $data);
    }

    public function updatepassword()
    {
        if ($this->request->isAJAX()) {
            $nameuser = session()->nameuser;
            $passlama = $this->request->getPost('passlama');
            $passbaru = $this->request->getPost('passbaru');
            $confirmpass = $this->request->getPost('confirmpass');

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'passlama' => [
                    'rules' => 'required',
                    'label' => 'Password Lama',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'passbaru' => [
                    'rules' => 'required',
                    'label' => 'Password Baru',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'confirmpass' => [
                    'rules' => 'required|matches[passbaru]',
                    'label' => 'Confirm Password',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'matches' => '{field} harus sama'
                    ]
                ]
            ]);

            if (!$valid) {
                $error = [
                    'passlama' => $validation->getError('passlama'),
                    'passbaru' => $validation->getError('passbaru'),
                    'confirmpass' => $validation->getError('confirmpass')
                ];

                $json = [
                    'error' => $error
                ];
            } else {
                $modelUser = new UsersModel();
                $rowData = $modelUser->find($nameuser);
                $passUser = $rowData['userpassword'];

                if (password_verify($passlama, $passUser)) {
                    $hashPasswordBaru = password_hash($passbaru, PASSWORD_DEFAULT);
                    $modelUser->update($nameuser, [
                        'userpassword' => $hashPasswordBaru
                    ]);

                    $json = [
                        'sukses' => 'Password anda berhasil diganti'
                    ];
                } else {
                    $error = [
                        'passlama' => 'Password lama tidak sama'
                    ];

                    $json = [
                        'error' => $error
                    ];
                }
            }
            echo json_encode($json);
        }
    }
}
