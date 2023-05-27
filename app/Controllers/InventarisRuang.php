<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\DetailInventarisRuang;
use App\Models\InventarisRuangModel;
use App\Models\KondisiModel;
use App\Models\TempInventarisRuang;
use App\Models\ModelDataBarang;
use Config\Services;

class InventarisRuang extends BaseController
{
    protected $BarangModel;
    protected $KondisiModel;
    protected $TempInventarisRuang;
    protected $inventarisRuangModel;
    protected $DetailInventarisRuangModel;

    public function __construct()
    {
        $this->BarangModel = new BarangModel();
        $this->KondisiModel = new KondisiModel();
        $this->TempInventarisRuang = new TempInventarisRuang();
        $this->inventarisRuangModel = new InventarisRuangModel();
        $this->DetailInventarisRuangModel = new DetailInventarisRuang();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Inventaris Ruang',
            'data' => $this->BarangModel->findAll(),
            'datakondisi' => $this->KondisiModel->findAll()
        ];
        return view('inventarisruang/forminput', $data);
    }

    public function data()
    {

        $dataInventarisRuang = $this->inventarisRuangModel->tampildata();

        $data = [
            'title' => 'Data barang pinjam',
            'tampildata' => $dataInventarisRuang,
        ];
        return view('inventarisruang/viewdata', $data);
    }

    public function datauser()
    {

        $dataInventarisRuang = $this->inventarisRuangModel->tampildata();

        $data = [
            'title' => 'Data barang pinjam',
            'tampildata' => $dataInventarisRuang,
        ];
        return view('inventarisruang/viewdatauser', $data);
    }


    public function dataTemp()
    {
        if ($this->request->isAJAX()) {
            $inventaris = $this->request->getPost('inventaris');

            $modelTemp = new TempInventarisRuang();
            $data = [
                'datatemp' => $modelTemp->tampilDataTemp($inventaris)
            ];

            $json = [
                'data' => view('inventarisruang/dataTemp', $data)
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function modalData()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('inventarisruang/modaldatabrg')
            ];
            echo json_encode($json);
        }
    }

    public function listData()
    {
        $request = Services::request();
        $datamodel = new ModelDataBarang($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];

                $tombolpilih = " <button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"pilih('" . $list->brgkode . "','" . $list->brgnama . "','" . $list->katnama . "')\">Pilih</button>";
                // $tombolhapus = " <button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"hapus('" . $list->nis . "','" . $list->namasiswa . "')\">Hapus</button>";

                $row[] = $no;
                $row[] = $list->brgkode;
                $row[] = $list->brgnama;
                $row[] = $list->katnama;
                $row[] = $list->brgstok;
                $row[] = $list->satnama;
                $row[] = $tombolpilih;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all(),
                "recordsFiltered" => $datamodel->count_filtered(),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    public function simpanTemp()
    {
        if ($this->request->isAJAX()) {
            $inventaris = $this->request->getPost('inventaris');
            $kodebarang = $this->request->getPost('kdbrg');
            $jumlah = $this->request->getPost('jumlah');
            $kategori = $this->request->getPost('kategori');
            $kondisi = $this->request->getPost('kondisi');

            $ambildatabarang = $this->BarangModel->find($kodebarang);
            $stokbarang = $ambildatabarang['brgstok'];

            if ($jumlah > intval($stokbarang)) {
                $json = [
                    'error' => 'Stok tidak mencukupi...'
                ];
            } else {
                $this->TempInventarisRuang->insert([
                    'detinven' => $inventaris,
                    'detbrgkode' => $kodebarang,
                    'detjml' => $jumlah,
                    'detkatid' => $kategori,
                    'detkondisi' => $kondisi
                ]);
                $json = [
                    'sukses' => 'Item berhasil ditambahkan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('iddetail');
            $this->TempInventarisRuang->delete($id);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function SelesaiTransaksi()
    {
        if ($this->request->isAJAX()) {
            $inventaris = $this->request->getPost('inventaris');
            $tanggal = $this->request->getPost('tgl');

            $modalTemp = new TempInventarisRuang();
            $dataTemp = $modalTemp->getwhere(['detinven' => $inventaris]);

            if ($dataTemp->getNumRows() == 0) {
                $json = [
                    'error' => 'Maaf, data item untuk faktur ini belum ada'
                ];
            } else {

                $totalSubTotal = 0;
                foreach ($dataTemp->getResultArray() as $total) :
                    $totalSubTotal += $total['detjml'];
                endforeach;

                //simpan ke tabel barang masuk

                $this->inventarisRuangModel->insert([
                    'id' => $inventaris,
                    'tanggal' => $tanggal,
                    'totalbarang' => $totalSubTotal
                ]);

                //simpan data ke tabel detail barang masuk
                foreach ($dataTemp->getResultArray() as $row) :
                    $this->DetailInventarisRuangModel->insert([
                        'detinven' => $row['detinven'],
                        'detbrgkode' => $row['detbrgkode'],
                        'detjml' => $row['detjml'],
                        'detkatid' => $row['detkatid'],
                        'detkondisi' => $row['detkondisi']
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

    public function detailItem()
    {

        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $data = [
                'tampildatadetail' => $this->DetailInventarisRuangModel->dataDetail($id)
            ];

            $json = [
                'data' => view('inventarisruang/modaldetailitem', $data)
            ];

            echo json_encode($json);
        }
    }

    public function editinventarisruang($id)
    {
        $cekid = $this->inventarisRuangModel->cekFaktur($id);

        if ($cekid->getNumRows() > 0) {
            $row = $cekid->getRowArray();

            $data = [
                'title' => 'Edit Inventaris Ruang',
                'noid' => $row['id'],
                'tanggal' => $row['tanggal'],
                'datakondisi' => $this->KondisiModel->findAll()
            ];
            return view('inventarisruang/formedit', $data);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function dataDetail()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('inventaris');

            $data = [
                'datadetail' => $this->DetailInventarisRuangModel->dataDetail($id)
            ];
            $totalBarang = number_format($this->DetailInventarisRuangModel->ambilTotalBarang($id));
            $json = [
                'data' => view('inventarisruang/dataDetail', $data),
                'totalbarang' => $totalBarang
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function hapusItemDetail()
    {
        if ($this->request->isAJAX()) {
            $iddetail = $this->request->getPost('id');
            $id = $this->request->getPost('inventaris');

            $this->DetailInventarisRuangModel->delete($iddetail);

            $ambilTotalBarang = $this->DetailInventarisRuangModel->ambilTotalBarang($id);

            $this->inventarisRuangModel->update($id, [
                'totalbarang' => $ambilTotalBarang
            ]);

            $json = [
                'sukses' => 'Item berhasil dihapus'
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

            $ambildata = $this->DetailInventarisRuangModel->ambilDetailId($iddetail);

            $row = $ambildata->getRowArray();

            $data = [
                'kodebarang' => $row['detbrgkode'],
                'namabarang' => $row['brgnama'],
                'kategori' => $row['detkatid'],
                'jumlah' => $row['detjml'],
                'kondisi' => $row['detkondisi'],
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
            $id = $this->request->getPost('inventaris');
            $kodebarang = $this->request->getPost('kdbrg');
            $jumlah = $this->request->getPost('jumlah');
            $kategori = $this->request->getPost('kategori');
            $kondisi = $this->request->getPost('kondisi');

            $ambildatabarang = $this->BarangModel->find($kodebarang);
            $stokbarang = $ambildatabarang['brgstok'];

            if ($jumlah > intval($stokbarang)) {
                $json = [
                    'error' => 'Stok tidak mencukupi...'
                ];
            } else {
                $this->DetailInventarisRuangModel->insert([
                    'detinven' => $id,
                    'detbrgkode' => $kodebarang,
                    'detjml' => $jumlah,
                    'detkatid' => $kategori,
                    'detkondisi' => $kondisi
                ]);

                $ambilTotalBarang = $this->DetailInventarisRuangModel->ambilTotalBarang($id);

                $this->inventarisRuangModel->update($id, [
                    'totalbarang' => $ambilTotalBarang
                ]);

                $json = [
                    'sukses' => 'Item berhasil ditambahkan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function updateItem()
    {
        if ($this->request->isAJAX()) {
            $kodebarang = $this->request->getPost('kdbrg');
            $jumlah = $this->request->getPost('jumlah');
            $kategori = $this->request->getPost('kategori');
            $kondisi = $this->request->getPost('kondisi');
            $iddetail = $this->request->getPost('iddetail');
            $id = $this->request->getPost('inventaris');

            $this->DetailInventarisRuangModel->update($iddetail, [
                'detjml' => $jumlah,
                'detkondisi' => $kondisi
            ]);

            $ambilTotalBarang = $this->DetailInventarisRuangModel->ambilTotalBarang($id);

            $this->inventarisRuangModel->update($id, [
                'totalbarang' => $ambilTotalBarang
            ]);

            $json = [
                'sukses' => 'Item berhasil diupdate'
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function hapusTransaksi()
    {
        $inventaris = $this->request->getPost('id');

        //digunakan untuk menghapus data transaksi barang masuk menggunakan modelling data 
        $db = \Config\Database::connect();

        $db->table('detail_inventarisruang')->delete(['detinven' => $inventaris]);
        $this->inventarisRuangModel->delete($inventaris);

        $json = [
            'sukses' => "Transaksi : '$inventaris' berhasil dihapus."
        ];
        echo json_encode($json);
    }
}
