<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\DatapeminjamanbarangModel;
use App\Models\DetailbarangpinjamModel;
use App\Models\KategoriModel;
use App\Models\ModelDataBarang;
use App\Models\ModelDataSiswa;
use App\Models\SatuanModel;
use Config\Services;

class DataPeminjamanBarang extends BaseController
{
    protected $BarangModel;
    protected $KategoriModel;
    protected $SatuanModel;
    protected $DataPeminjamanBarangModel;


    public function __construct()
    {
        $this->BarangModel = new BarangModel();
        $this->KategoriModel =  new KategoriModel();
        $this->SatuanModel = new SatuanModel();
        $this->DataPeminjamanBarangModel = new DatapeminjamanbarangModel();
    }

    public function index()
    {

        $tombolcari = $this->request->getPost('tombolcaripembarang');
        if (isset($tombolcari)) {
            $cari = $this->request->getPost('caripeminjamanbarang');
            session()->set('cari_barang', $cari);
            redirect()->to('/barang/index');
        } else {
            $cari = session()->get('cari_barang');
        }

        //$dataPeminjamanBarang = $cari ? $this->DataPeminjamanBarangModel->tampildata($cari)->paginate(5, 'data_barang_peminjaman') : $this->DataPeminjamanBarangModel->tampil()->paginate(5, 'data_barang_peminjaman');


        $currentPage = $this->request->getVar('page_data_barang_peminjaman') ? $this->request->getVar('page_data_barang_peminjaman') :
            1;

        $modelDetailPinjam = new DetailbarangpinjamModel();

        $data = [
            'title' => 'Data Peminjaman Barang',
            'tampildata' => $modelDetailPinjam->findAll(),
            'pager' => $this->DataPeminjamanBarangModel->pager,
            'currentPage' => $currentPage,
            'cari' => $cari
        ];
        return view('peminjaman/viewpeminjamanbarang', $data);
    }

    public function formtambah()
    {
        $data = [
            'title' => 'Form Tambah Data Peminjaman Barang',
            'databarang' => $this->BarangModel->findAll(),
        ];
        return view('peminjaman/tambahpeminjamanbarang', $data);
    }

    public function ambilDataBarang()
    {
        if ($this->request->isAJAX()) {
            $kodebarang = $this->request->getPost('kodebarang');

            $ambildata = $this->BarangModel->find($kodebarang);

            if ($ambildata == null) {
                $json = [
                    'error' => 'Data barang tidak ditemukan'
                ];
            } else {
                $data = [
                    'namabarang' => $ambildata['brgnama'],
                    'kategori' => $ambildata['brgkatid'],
                    'satuan' => $ambildata['brgsatid'],
                    'letak' => $ambildata['letak'],
                    'spesifikasi' => $ambildata['spesifikasi'],
                ];

                $json = [
                    'sukses' => $data
                ];
            }

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function cariDataBarang()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('peminjaman/modalcaribarang')
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function detailCariBarang()
    {
        if ($this->request->isAJAX()) {
            $cari = $this->request->getPost('cari');

            $data = $this->BarangModel->tampildata_cari($cari)->get();

            if ($data != null) {
                $json = [
                    'data' => view('peminjaman/detaildatabarang', [
                        'tampildata' => $data
                    ])
                ];
                echo json_encode($json);
            }
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function simpandata()
    {
        $kodebarang = $this->request->getVar('kdbarang');
        $namabarang = $this->request->getVar('namabarang');
        $kategori = $this->request->getVar('kategori');
        $satuan = $this->request->getVar('satuan');
        $stok = $this->request->getVar('stok');
        $letak = $this->request->getVar('letak');
        $spesifikasi = $this->request->getVar('spesifikasi');
        $validation = \config\Services::validation();

        $valid = $this->validate([
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
            'letak' => [
                'rules' => 'required',
                'label' => 'letak',
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
            return redirect()->to('/datapeminjamanbarang/formtambah');
        } else {
            $this->DataPeminjamanBarangModel->insert([
                'pbrgkode' => $kodebarang,
                'pbrgnama' => $namabarang,
                'pkatid' => $kategori,
                'psatid' => $satuan,
                'pstok' => $stok,
                'letak' => $letak,
                'spesifikasi' => $spesifikasi
            ]);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
               Data barang dengan nama <strong>' . $namabarang . '</strong> berhasil disimpan
              </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/datapeminjamanbarang/formtambah');
        }
    }

    public function hapus($kode)
    {
        $rowData = $this->DataPeminjamanBarangModel->find($kode);
        if ($rowData) {
            $this->DataPeminjamanBarangModel->delete($kode);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                Data barang dengan kode <strong>' . $kode . '</strong> berhasil dihapus
              </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/datapeminjamanbarang/index');
        } else {
            exit('Maaf data tidak ditemukan');
        }
    }




    //tidak ada edit data
    public function formedit($kode)
    {
        $rowData = $this->DataPeminjamanBarangModel->find($kode);
        if ($rowData) {
            $data = [
                'title' => 'Form Tambah Barang',
                'kodebarang' => $rowData['pbrgkode'],
                'namabarang' => $rowData['pbrgnama'],
                'kategori' => $rowData['pkatid'],
                'satuan' => $rowData['psatid'],
                'stok' => $rowData['pstok'],
                'letak' => $rowData['letak'],
                'spesifikasi' => $rowData['spesifikasi'],
                'databarang' => $this->BarangModel->findAll(),
            ];
            return view('peminjaman/editpeminjamanbarang', $data);
        } else {
            $pesan = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
               Data barang tidak ditemukan
              </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/datapeminjamanbarang/index');
        }
    }

    public function updatedata()
    {
        $kodebarang = $this->request->getVar('kdbarang');
        $namabarang = $this->request->getVar('namabarang');
        $kategori = $this->request->getVar('kategori');
        $satuan = $this->request->getVar('satuan');
        $stok = $this->request->getVar('stok');
        $letak = $this->request->getVar('letak');
        $spesifikasi = $this->request->getVar('spesifikasi');
        $validation = \config\Services::validation();

        $valid = $this->validate([
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
            'letak' => [
                'rules' => 'required',
                'label' => 'letak',
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
            return redirect()->to('/datapeminjamanbarang/formedit');
        } else {
            $this->DataPeminjamanBarangModel->update([
                'pbrgkode' => $kodebarang,
                'pbrgnama' => $namabarang,
                'pkatid' => $kategori,
                'psatid' => $satuan,
                'pstok' => $stok,
                'letak' => $letak,
                'spesifikasi' => $spesifikasi
            ]);

            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
               Data barang dengan kode <strong>' . $kodebarang . '</strong> berhasil disimpan
              </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/datapeminjamanbarang/index');
        }
    }
}
