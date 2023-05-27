<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangmasukModel;
use App\Models\BarangModel;
use App\Models\DetailbarangmasukModel;
use App\Models\TempbarangmasukModel;


class Barangmasuk extends BaseController
{
    protected $Barang;
    protected $TempBarangmasuk;
    protected $BarangMasuk;
    protected $DetailBarangMasuk;


    public function __construct()
    {
        $this->TempBarangMasuk = new TempbarangmasukModel();
        $this->Barang = new BarangModel();
        $this->BarangMasuk = new BarangmasukModel();
        $this->DetailBarangMasuk = new DetailbarangmasukModel();
    }


    public function index()
    {
        $data = [
            'title' => 'Data barang masuk',
        ];
        return view('barangmasuk/forminput', $data);
    }

    public function dataTemp()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modelTemp = new TempbarangmasukModel();
            $data = [
                'datatemp' => $modelTemp->tampilDataTemp($faktur),
            ];

            $json = [
                'data' => view('barangmasuk/datatemp', $data)
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function ambilDataBarang()
    {
        if ($this->request->isAJAX()) {
            $kodebarang = $this->request->getPost('kodebarang');

            $ambildata = $this->Barang->find($kodebarang);

            if ($ambildata == null) {
                $json = [
                    'error' => 'Data barang tidak ditemukan'
                ];
            } else {
                $data = [
                    'namabarang' => $ambildata['brgnama']
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

    public function simpanTemp()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $harga = $this->request->getPost('harga');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelTempBarang = new TempbarangmasukModel();

            $modelTempBarang->insert([
                'detfaktur' => $faktur,
                'detbrgkode' => $kodebarang,
                'detharga' => $harga,
                'detjml' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($harga)
            ]);
            $json = [
                'sukses' => 'Item berhasil ditambahkan'
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $modelTempBarang = new TempbarangmasukModel();
            $modelTempBarang->delete($id);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function cariDataBarang()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('barangmasuk/modalcaribarang')
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

            $data = $this->Barang->tampildata_cari($cari)->get();

            if ($data != null) {
                $json = [
                    'data' => view('barangmasuk/detaildatabarang', [
                        'tampildata' => $data
                    ])
                ];
                echo json_encode($json);
            }
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function SelesaiTransaksi()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $tglfaktur = $this->request->getPost('tglfaktur');

            $modalTemp = new TempbarangmasukModel();
            $dataTemp = $modalTemp->getwhere(['detfaktur' => $faktur]);

            if ($dataTemp->getNumRows() == 0) {
                $json = [
                    'error' => 'Maaf, data item untuk faktur ini belum ada'
                ];
            } else {
                //simpan ke tabel barang masuk
                $totalSubTotal = 0;
                foreach ($dataTemp->getResultArray() as $total) :
                    $totalSubTotal += intval($total['detsubtotal']);
                endforeach;

                $this->BarangMasuk->insert([
                    'faktur' => $faktur,
                    'tglfaktur' => $tglfaktur,
                    'totalharga' => $totalSubTotal
                ]);

                //simpan data ke tabel detail barang masuk
                $DetailBarangMasukModel = new DetailbarangmasukModel();
                foreach ($dataTemp->getResultArray() as $row) :
                    $DetailBarangMasukModel->insert([
                        'detfaktur' => $row['detfaktur'],
                        'detbrgkode' => $row['detbrgkode'],
                        'detharga' => $row['detharga'],
                        'detjml' => $row['detjml'],
                        'detsubtotal' => $row['detsubtotal']
                    ]);
                endforeach;

                // hapus data yang ada ditabel temporary berdasarkan faktur
                $modalTemp->emptyTable();

                $json = [
                    'sukses' => 'Transaksi berhasil disimpan'
                ];
            }

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function data()
    {
        $tombolcari = $this->request->getPost('tombolCari');

        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            session()->set('cari_faktur', $cari);
            redirect()->to('/barangmasuk/data');
        } else {
            $cari = session()->get('cari_faktur');
        }

        $totaldata = $cari ? $this->BarangMasuk->tampildata_cari($cari)->countAllResults() : $this->BarangMasuk->countAllResults();
        $dataBarangMasuk = $cari ? $this->BarangMasuk->tampildata_cari($cari)->paginate(5, 'barangmasuk') : $this->BarangMasuk->paginate(5, 'barangmasuk');
        $currentPage = $this->request->getVar('page_barangmasuk') ? $this->request->getVar('page_barangmasuk') :
            1;
        $data = [
            'title' => 'Data Barang Masuk',
            'tampildata' => $dataBarangMasuk,
            'pager' => $this->BarangMasuk->pager,
            'currentPage' => $currentPage,
            'totaldata' => $totaldata,
            'cari' => $cari
        ];
        return view('barangmasuk/viewdata', $data);
    }

    public function detailItem()
    {

        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modeldetail =  new DetailbarangmasukModel();
            $data = [
                'tampildatadetail' => $modeldetail->dataDetail($faktur)
            ];

            $json = [
                'data' => view('barangmasuk/modaldetailitem', $data)
            ];

            echo json_encode($json);
        }
    }

    public function editbarangmasuk($faktur)
    {
        $cekFaktur = $this->BarangMasuk->cekFaktur($faktur);

        if ($cekFaktur->getNumRows() > 0) {
            $row = $cekFaktur->getRowArray();

            $data = [
                'title' => 'Edit barang masuk',
                'nofaktur' => $row['faktur'],
                'tanggal' => $row['tglfaktur']
            ];
            return view('barangmasuk/formedit', $data);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function dataDetail()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $data = [
                'datadetail' => $this->DetailBarangMasuk->dataDetail($faktur),
            ];

            $totalHargaFaktur = number_format($this->DetailBarangMasuk->ambilTotalHarga($faktur), 0, ",", ".");
            $json = [
                'data' => view('barangmasuk/datadetail', $data),
                'totalharga' => $totalHargaFaktur
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function editItem()
    {
        if ($this->request->isAJAX()) {
            $iddetail = $this->request->getPost('iddetail');

            $ambildata = $this->DetailBarangMasuk->ambilDetailId($iddetail);

            $row = $ambildata->getRowArray();

            $data = [
                'kodebarang' => $row['detbrgkode'],
                'namabarang' => $row['brgnama'],
                'harga' => $row['detharga'],
                'jumlah' => $row['detjml']
            ];

            $json = [
                'sukses' => $data
            ];

            return json_encode($json);
        }
    }

    public function simpanDetail()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $harga = $this->request->getPost('harga');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelDetail = new DetailbarangmasukModel();

            $modelDetail->insert([
                'detfaktur' => $faktur,
                'detbrgkode' => $kodebarang,
                'detharga' => $harga,
                'detjml' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($harga)
            ]);

            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);

            $this->BarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item berhasil ditambahkan'
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function updateItem()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $harga = $this->request->getPost('harga');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');
            $iddetail = $this->request->getPost('iddetail');

            $modelDetail = new DetailbarangmasukModel();

            $modelDetail->update($iddetail, [
                'detharga' => $harga,
                'detjml' => $jumlah,
                'detsubtotal' => intval($jumlah) * intval($harga)
            ]);

            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);

            $this->BarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item berhasil diupdate'
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function hapusItemDetail()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new DetailbarangmasukModel();
            $modelDetail->delete($id);

            $ambilTotalHarga = $modelDetail->ambilTotalHarga($faktur);
            $this->BarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);



            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function hapusTransaksi()
    {
        $faktur = $this->request->getPost('faktur');

        //digunakan untuk menghapus data transaksi barang masuk menggunakan modelling data 
        $db = \Config\Database::connect();

        $modelBarangMasuk = new BarangmasukModel();

        $db->table('detail_barangmasuk')->delete(['detfaktur' => $faktur]);
        $modelBarangMasuk->delete($faktur);

        $json = [
            'sukses' => "Transaksi dengan faktur : '$faktur' berhasil dihapus."
        ];
        echo json_encode($json);
    }

    public function datauser()
    {
        $tombolcari = $this->request->getPost('tombolCari');

        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            session()->set('cari_faktur', $cari);
            redirect()->to('/barangmasuk/datauser');
        } else {
            $cari = session()->get('cari_faktur');
        }

        $totaldata = $cari ? $this->BarangMasuk->tampildata_cari($cari)->countAllResults() : $this->BarangMasuk->countAllResults();
        $dataBarangMasuk = $cari ? $this->BarangMasuk->tampildata_cari($cari)->paginate(5, 'barangmasuk') : $this->BarangMasuk->paginate(5, 'barangmasuk');
        $currentPage = $this->request->getVar('page_barangmasuk') ? $this->request->getVar('page_barangmasuk') :
            1;
        $data = [
            'title' => 'Data Barang Masuk',
            'tampildata' => $dataBarangMasuk,
            'pager' => $this->BarangMasuk->pager,
            'currentPage' => $currentPage,
            'totaldata' => $totaldata,
            'cari' => $cari
        ];
        return view('barangmasuk/viewdatauser', $data);
    }
}
