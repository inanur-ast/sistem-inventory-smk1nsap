<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Database\Migrations\Satuan;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\SatuanModel;
use \Hermawan\DataTables\DataTable;


class Barang extends BaseController
{

    public function __construct()
    {
        $this->barang = new BarangModel();
        $this->kategori =  new KategoriModel();
        $this->satuan = new SatuanModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Barang',
        ];
        return view('barang/viewdatabarang_new', $data);
    }

    public function listData()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $builder = $db->table('barang')->join('kategori', 'brgkatid=katid')->join('satuan', 'brgsatid=satid')->select('brgkode, brgnama, katnama, satnama, brgstok, spesifikasi');

            return DataTable::of($builder)
                ->addNumbering('nomor')
                ->add('aksi', function ($row) {
                    return "<button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"hapus('" . $row->brgkode . "')\"> <i class=\"fa fa-trash-alt\"></i></button>
                    <button type=\"button\" class=\"btn btn-info btn-sm\" onclick=\"edit('" . $row->brgkode . "')\"> <i class=\"fa fa-edit\"></i></button>";
                })
                ->toJson(true);
        }
    }

    public function indexuser()
    {
        $data = [
            'title' => 'Data Barang',
        ];
        return view('barang/viewbaranguser', $data);
    }

    public function listDatauser()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $builder = $db->table('barang')->join('kategori', 'brgkatid=katid')->join('satuan', 'brgsatid=satid')->select('brgkode, brgnama, katnama, satnama, brgstok, spesifikasi');

            return DataTable::of($builder)
                ->addNumbering('nomor')
                ->toJson(true);
        }
    }

    // public function index()
    // {
    //     $tombolcari = $this->request->getPost('tombolcari');
    //     if (isset($tombolcari)) {
    //         $cari = $this->request->getPost('cari');
    //         session()->set('cari_barang', $cari);
    //         redirect()->to('/barang/index');
    //     } else {
    //         $cari = session()->get('cari_barang');
    //     }

    //     $dataBarang = $cari ? $this->barang->tampildata_cari($cari)->paginate(5, 'barang') : $this->barang->tampildata()->paginate(5, 'barang');


    //     $currentPage = $this->request->getVar('page_barang') ? $this->request->getVar('page_barang') :
    //         1;


    //     $data = [
    //         'title' => 'Data Barang',
    //         'tampildata' => $dataBarang,
    //         'pager' => $this->barang->pager,
    //         'currentPage' => $currentPage,
    //         'cari' => $cari
    //     ];
    //     return view('barang/viewbarang', $data);
    // }

    public function formtambah()
    {
        $data = [
            'title' => 'Form Tambah Barang',
            'datakategori' => $this->kategori->findAll(),
            'datasatuan' => $this->satuan->findAll()
        ];
        return view('barang/tambahbarang', $data);
    }

    public function auto()
    {
        $data =  $this->barang->generateCode();
        return json_encode($data);
    }

    public function simpandata()
    {
        $kodebrg = $this->request->getVar('kodebrg');
        $namabarang = $this->request->getVar('namabarang');
        $kategori = $this->request->getVar('kategori');
        $satuan = $this->request->getVar('satuan');
        $stok = $this->request->getVar('stok');
        $spesifikasi = $this->request->getVar('spesifikasi');
        $validation = \config\Services::validation();

        $valid = $this->validate([
            'kodebrg' => [
                'rules' => 'required|is_unique[barang.brgkode]',
                'label' => 'Kode barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_unique' => '{field} sudah ada...'
                ]
            ],
            'namabarang' => [
                'rules' => 'required',
                'label' => 'Nama barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'kategori' => [
                'rules' => 'required',
                'label' => 'Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'satuan' => [
                'rules' => 'required',
                'label' => 'Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'stok' => [
                'rules' => 'required|numeric',
                'label' => 'Stok',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} hanya dalam bentuk angka'
                ]
            ],

            'spesifikasi' => [
                'rules' => 'required',
                'label' => 'spesifikasi',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} hanya dalam bentuk angka'
                ]
            ],
        ]);

        if (!$valid) {
            $pesan = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
               ' . $validation->listErrors() . '
              </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/barang/formtambah');
        } else {
            $this->barang->insert([
                'brgkode' => $kodebrg,
                'brgnama' => $namabarang,
                'brgkatid' => $kategori,
                'brgsatid' => $satuan,
                'brgstok' => $stok,
                'spesifikasi' => $spesifikasi
            ]);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
               Data barang dengan kode <strong>' . $kodebrg . '</strong> berhasil disimpan
              </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/barang/formtambah');
        }
    }

    public function formedit($kode)
    {
        $rowData = $this->barang->find($kode);
        if ($rowData) {
            $data = [
                'title' => 'Form Tambah Barang',
                'kodebrg' => $rowData['brgkode'],
                'namabarang' => $rowData['brgnama'],
                'kategori' => $rowData['brgkatid'],
                'satuan' => $rowData['brgsatid'],
                'stok' => $rowData['brgstok'],
                'spesifikasi' => $rowData['spesifikasi'],
                'datakategori' => $this->kategori->findAll(),
                'datasatuan' => $this->satuan->findAll()
            ];
            return view('barang/editbarang', $data);
        } else {
            $pesan = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
               Data barang tidak ditemukan
              </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/barang/index');
        }
    }

    public function updatedata()
    {
        $kodebrg = $this->request->getVar('kodebrg');
        $namabarang = $this->request->getVar('namabarang');
        $kategori = $this->request->getVar('kategori');
        $satuan = $this->request->getVar('satuan');
        $stok = $this->request->getVar('stok');
        $spesifikasi = $this->request->getVar('spesifikasi');


        $validation = \config\Services::validation();

        $valid = $this->validate([
            'namabarang' => [
                'rules' => 'required',
                'label' => 'Nama barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'kategori' => [
                'rules' => 'required',
                'label' => 'Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'satuan' => [
                'rules' => 'required',
                'label' => 'Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'stok' => [
                'rules' => 'required|numeric',
                'label' => 'Stok',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} hanya dalam bentuk angka'
                ]
            ],
            'spesifikasi' => [
                'rules' => 'required',
                'label' => 'spesifikasi',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} hanya dalam bentuk angka'
                ]
            ],
        ]);

        if (!$valid) {
            $pesan = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
               ' . $validation->listErrors() . '
              </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/barang/formedit' . $kodebrg);
        } else {
            $this->barang->update($kodebrg, [
                'brgnama' => $namabarang,
                'brgkatid' => $kategori,
                'brgsatid' => $satuan,
                'brgstok' => $stok,
                'spesifikasi' => $spesifikasi
            ]);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
               Data barang dengan kode <strong>' . $kodebrg . '</strong> berhasil diupdate
              </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/barang/index');
        }
    }

    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $kodebrg = $this->request->getPost('brgkode');

            $db = \Config\Database::connect();

            $db->table('barang')->delete(['brgkode' => $kodebrg]);
            $this->barang->delete($kodebrg);

            $json = [
                'sukses' => 'Transaksi berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }
}
