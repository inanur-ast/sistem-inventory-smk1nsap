<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use \Hermawan\DataTables\DataTable;

class Kategori extends BaseController
{
    public function __construct()
    {
        $this->kategori =  new KategoriModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Kategori',
        ];
        return view('kategori/viewkategori_new', $data);
    }

    public function listData()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $builder = $db->table('kategori')->select('katid, katnama');

            return DataTable::of($builder)
                ->addNumbering('nomor')
                ->add('aksi', function ($row) {
                    return "<button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"hapus('" . $row->katid . "')\"> <i class=\"fa fa-trash-alt\"></i></button>
                    <button type=\"button\" class=\"btn btn-info btn-sm\" onclick=\"edit('" . $row->katid . "')\"> <i class=\"fa fa-edit\"></i></button>";
                })
                ->toJson(true);
        }
    }
    // public function index()
    // {

    //     $data = [
    //         'title' => 'Data Kategori',
    //         'tampildata' => $this->kategori->findAll()

    //     ];
    //     return view('kategori/viewkategori', $data);
    // }

    public function formtambah()
    {
        $data = [
            'title' => 'Form Tambah Kategori',
        ];
        return view('kategori/tambahkategori', $data);
    }

    public function simpandata()
    {
        $namakategori = $this->request->getVar('namakategori');
        $validation = \config\Services::validation();

        $valid = $this->validate([
            'namakategori' => [
                'rules' => 'required',
                'label' => 'Nama Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaKategori' => '<br><div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/formtambah');
        } else {
            $this->kategori->insert([
                'katnama' => $namakategori
            ]);

            $pesan = [
                'sukses' => '<br><div class="alert alert-success"> Data kategori berhasil ditambahkan</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/index');
        }
    }

    public function formedit($id)
    {
        $rowData = $this->kategori->find($id);
        if ($rowData) {
            $data = [
                'title' => 'Form Tambah Kategori',
                'id' => $id,
                'nama' => $rowData['katnama'],
            ];
            return view('kategori/editkategori', $data);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function updatedata()
    {
        $idkategori = $this->request->getVar('idkategori');
        $namakategori = $this->request->getVar('namakategori');
        $validation = \config\Services::validation();

        $valid = $this->validate([
            'namakategori' => [
                'rules' => 'required',
                'label' => 'Nama Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaKategori' => '<br><div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/formedit/' . $idkategori);
        } else {
            $this->kategori->Update($idkategori, [
                'katnama' => $namakategori
            ]);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
               Data kategori berhasil diupdate
              </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/index');
        }
    }

    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('katid');
            $db = \Config\Database::connect();

            $db->table('kategori')->delete(['katid' => $id]);
            $this->kategori->delete($id);

            $json = [
                'sukses' => 'Transaksi berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }
}
