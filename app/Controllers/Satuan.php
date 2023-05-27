<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SatuanModel;
use \Hermawan\DataTables\DataTable;

class Satuan extends BaseController
{

    public function __construct()
    {
        $this->satuan =  new SatuanModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Satuan',
        ];
        return view('satuan/viewsatuan_new', $data);
    }

    public function listData()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $builder = $db->table('satuan')->select('satid, satnama');

            return DataTable::of($builder)
                ->addNumbering('nomor')
                ->add('aksi', function ($row) {
                    return "<button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"hapus('" . $row->satid . "')\"> <i class=\"fa fa-trash-alt\"></i></button>
                    <button type=\"button\" class=\"btn btn-info btn-sm\" onclick=\"edit('" . $row->satid . "')\"> <i class=\"fa fa-edit\"></i></button>";
                })
                ->toJson(true);
        }
    }

    // public function index()
    // {
    //     $data = [
    //         'title' => 'Data Satuan',
    //         'tampildata' => $this->satuan->findAll(),
    //     ];
    //     return view('satuan/viewsatuan', $data);
    // }

    public function formtambah()
    {
        $data = [
            'title' => 'Form Tambah Satuan',
        ];
        return view('satuan/tambahsatuan', $data);
    }

    public function simpandata()
    {
        $namasatuan = $this->request->getVar('namasatuan');
        $validation = \config\Services::validation();

        $valid = $this->validate([
            'namasatuan' => [
                'rules' => 'required',
                'label' => 'Nama Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaSatuan' => '<br><div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/formtambah');
        } else {
            $this->satuan->insert([
                'satnama' => $namasatuan
            ]);

            $pesan = [
                'sukses' => '<br><div class="alert alert-success"> Data satuan berhasil ditambahkan</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/index');
        }
    }

    public function formedit($id)
    {
        $rowData = $this->satuan->find($id);
        if ($rowData) {
            $data = [
                'title' => 'Form Tambah Satuan',
                'id' => $id,
                'nama' => $rowData['satnama'],
            ];
            return view('satuan/editsatuan', $data);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function updatedata()
    {
        $idsatuan = $this->request->getVar('idsatuan');
        $namasatuan = $this->request->getVar('namasatuan');
        $validation = \config\Services::validation();

        $valid = $this->validate([
            'namasatuan' => [
                'rules' => 'required',
                'label' => 'Nama satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaSatuan' => '<br><div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/formedit/' . $idsatuan);
        } else {
            $this->satuan->Update($idsatuan, [
                'satnama' => $namasatuan
            ]);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
               Data satuan berhasil diupdate
              </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/index');
        }
    }

    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('satid');
            $db = \Config\Database::connect();

            $db->table('satuan')->delete(['satid' => $id]);
            $this->satuan->delete($id);

            $json = [
                'sukses' => 'Transaksi berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }
}
