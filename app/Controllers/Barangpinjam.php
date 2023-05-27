<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\BarangpinjamModel;
use App\Models\DatapeminjamanbarangModel;
use App\Models\DatasiswaModel;
use App\Models\DetailbarangpinjamModel;
use App\Models\KondisiModel;
use App\Models\ModelDataBarang;
use App\Models\ModelDataBarangPinjam;
use App\Models\ModelDataSiswa;
use App\Models\TempbarangpinjamModel;
use Config\Services;

class Barangpinjam extends BaseController
{
    protected $BarangpinjamModel;
    protected $DataPeminjamanBarangModel;
    protected $BarangModel;
    protected $kondisiModel;
    protected $TempbarangpinjamModel;
    protected $DetailbarangpinjamModel;

    public function __construct()
    {
        $this->BarangpinjamModel = new BarangpinjamModel();
        $this->DataPeminjamanBarangModel = new DatapeminjamanbarangModel();
        $this->BarangModel = new BarangModel();
        $this->kondisiModel = new KondisiModel();
        $this->TempbarangpinjamModel = new TempbarangpinjamModel();
        $this->DetailbarangpinjamModel = new DetailbarangpinjamModel();
    }

    public function auto()
    {
        $data =  $this->BarangpinjamModel->generateCode();
        return json_encode($data);
    }

    public function index()
    {
        $data = [
            'title' => 'Data barang pinjam',
            'nopeminjam' => $this->auto(),
            'data' => $this->BarangModel->findAll(),
            'datakondisi' => $this->kondisiModel->findAll()
        ];
        return view('barangpinjam/forminput', $data);
    }

    public function data()
    {
        $data = [
            'title' => 'Data barang pinjam',
        ];
        return view('barangpinjam/viewpeminjam', $data);
    }

    public function datauser()
    {
        $data = [
            'title' => 'Data barang pinjam',
        ];
        return view('barangpinjam/viewuser', $data);
    }

    public function dataTemp()
    {
        if ($this->request->isAJAX()) {
            $nopeminjam = $this->request->getPost('nopeminjam');

            $modelTemp = new TempbarangpinjamModel();
            $data = [
                'datatemp' => $modelTemp->tampilDataTemp($nopeminjam)
            ];

            $json = [
                'data' => view('barangpinjam/datatemp', $data)
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
                'data' => view('barangpinjam/modaldatabrg')
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
            $nopeminjam = $this->request->getPost('nopeminjam');
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
                $this->TempbarangpinjamModel->insert([
                    'detpeminjam' => $nopeminjam,
                    'detkdbrg' => $kodebarang,
                    'detjumlah' => $jumlah,
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
            $id = $this->request->getPost('id');
            $this->TempbarangpinjamModel->delete($id);

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
            $nopeminjam = $this->request->getPost('nopeminjam');
            $tglpinjam = $this->request->getPost('tglpinjam');
            $nis = $this->request->getPost('nis');
            $status = 'P';

            $modalTemp = new TempbarangpinjamModel();
            $dataTemp = $modalTemp->getwhere(['detpeminjam' => $nopeminjam]);

            if ($dataTemp->getNumRows() == 0) {
                $json = [
                    'error' => 'Maaf, data item untuk faktur ini belum ada'
                ];
            } else {
                //simpan ke tabel barang masuk

                $this->BarangpinjamModel->insert([
                    'id_peminjam' => $nopeminjam,
                    'tglpinjam' => $tglpinjam,
                    'idsiswa' => $nis,
                    'status' => $status
                ]);

                //simpan data ke tabel detail barang masuk
                foreach ($dataTemp->getResultArray() as $row) :
                    $this->DetailbarangpinjamModel->insert([
                        'detpeminjam' => $row['detpeminjam'],
                        'detkdbrg' => $row['detkdbrg'],
                        'detjumlah' => $row['detjumlah'],
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

    public function listDataPinjam()
    {
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');

        $request = Services::request();
        $datamodel = new ModelDataBarangPinjam($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables($tglawal, $tglakhir);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $db = \Config\Database::connect();

                $jumlahData = $db->table('detail_barangpinjam')->where('detpeminjam', $list->id_peminjam)->countAllResults();

                $tomboledit = " <button type=\"button\" class=\"btn btn-sm btn-info\" Title=\"Edit Detail Peminjaman\" 
                onclick=\"edit('" . $list->id_peminjam . "')\"><i class=\"fa fa-edit\"></i></button>";

                $tombolhapus = " <button type=\"button\" class=\"btn btn-sm btn-danger\" Title=\"Hapus Peminjaman\" 
                onclick=\"hapus('" . $list->id_peminjam . "')\"><i class=\"fa fa-trash-alt\"></i></button>";

                $tomboldetail = " <button type=\"button\" class=\"btn btn-sm btn-warning\" id=\"detail\" 
                Title=\"Detail Peminjaman\" onclick=\"detail('" . $list->id_peminjam . "')\"><i class=\"fa fa-eye\"></i></button>";

                $row[] = $no;
                $row[] = $list->id_peminjam;
                $row[] = $list->tglpinjam;
                $row[] = $list->namasiswa;
                $row[] = $jumlahData;
                $row[] = $list->tglkembali;
                $row[] = ($list->status == 'P') ? "<span class=\"badge badge-info\">Dipinjam</span>" : "<span class=\"badge badge-success\">Kembali</span>";
                $row[] = ($list->status == 'K') ? $tomboldetail : $tomboldetail . " " .  $tomboledit . " " . $tombolhapus;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all($tglawal, $tglakhir),
                "recordsFiltered" => $datamodel->count_filtered($tglawal, $tglakhir),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    public function hapusTransaksi()
    {
        if ($this->request->isAJAX()) {
            $nopeminjam = $this->request->getPost('id_peminjam');

            //digunakan untuk menghapus data transaksi barang masuk menggunakan modelling data 
            $db = \Config\Database::connect();

            $db->table('detail_barangpinjam')->delete(['detpeminjam' => $nopeminjam]);
            $this->BarangpinjamModel->delete($nopeminjam);

            $json = [
                'sukses' => 'Transaksi berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }

    public function edit($id_peminjam)
    {
        $modeldatasiswa = new DatasiswaModel();
        $rowData = $this->BarangpinjamModel->find($id_peminjam);
        $rowSiswa = $modeldatasiswa->find($rowData['idsiswa']);

        $data = [
            'title' => 'edit barang pinjam',
            'nopeminjam' => $id_peminjam,
            'tglpinjam' => $rowData['tglpinjam'],
            'namasiswa' => $rowSiswa['namasiswa'],
            'datakondisi' => $this->kondisiModel->findAll()
        ];

        return view('barangpinjam/formedit', $data);
    }

    public function TampilDataDetail()
    {
        if ($this->request->isAJAX()) {
            $nopeminjam = $this->request->getPost('nopeminjam');

            $data = [
                'datadetail' => $this->DetailbarangpinjamModel->tampilDataTemp($nopeminjam)
            ];

            $json = [
                'data' => view('barangpinjam/datadetail', $data)
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function hapusDetail()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $this->DetailbarangpinjamModel->delete($id);


            $json = [
                'sukses' => 'Item berhasil dihapus',
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function editItem()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $ambildata = $this->DetailbarangpinjamModel->ambilDetailId($id);

            $row = $ambildata->getRowArray();

            $data = [
                'kodebarang' => $row['detkdbrg'],
                'namabarang' => $row['brgnama'],
                'kategori' => $row['detkatid'],
                'jumlah' => $row['detjumlah'],
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
            $nopeminjam = $this->request->getPost('nopeminjam');
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
                $this->DetailbarangpinjamModel->insert([
                    'detpeminjam' => $nopeminjam,
                    'detkdbrg' => $kodebarang,
                    'detjumlah' => $jumlah,
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

    public function simpanUpdate()
    {
        if ($this->request->isAJAX()) {
            $jumlah = $this->request->getPost('jumlah');
            $kondisi = $this->request->getPost('kondisi');
            $id = $this->request->getPost('id');

            $this->DetailbarangpinjamModel->update($id, [
                'detkondisi' => $kondisi,
                'detjumlah' => $jumlah
            ]);

            $json = [
                'sukses' => 'Item berhasil diupdate'
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function SelesaiTransaksiEdit()
    {
        if ($this->request->isAJAX()) {
            $nopeminjam = $this->request->getPost('nopeminjam');
            $tglkembali = $this->request->getPost('tglkembali');
            $status = 'K';

            //simpan ke tabel barang masuk
            $this->BarangpinjamModel->update($nopeminjam, [
                'tglkembali' => $tglkembali,
                'status' => $status
            ]);

            $json = [
                'sukses' => 'Transaksi berhasil disimpan'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    public function detailpinjam($id_peminjam)
    {
        $modeldatasiswa = new DatasiswaModel();
        $rowData = $this->BarangpinjamModel->find($id_peminjam);
        $rowSiswa = $modeldatasiswa->find($rowData['idsiswa']);

        $data = [
            'title' => 'edit barang pinjam',
            'nopeminjam' => $id_peminjam,
            'tglpinjam' => $rowData['tglpinjam'],
            'namasiswa' => $rowSiswa['namasiswa'],
            'tglkembali' => $rowData['tglkembali'],
            'status' => $rowData['status'],

        ];
        return view('barangpinjam/formdetail', $data);
    }

    public function TampilDataFormDetail()
    {
        if ($this->request->isAJAX()) {
            $nopeminjam = $this->request->getPost('nopeminjam');

            $data = [
                'datadetail' => $this->DetailbarangpinjamModel->tampilDataTemp($nopeminjam)
            ];

            $json = [
                'data' => view('barangpinjam/dataformdetail', $data)
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa dipanggil');
        }
    }

    // public function cetakBeritaAcara()
    // {
    //     $tombolCetak = $this->request->getPost('btnCetak');

    //     if (isset($tombolCetak)) {

    //         $data = [
    //             'title' => 'Berita Acara',
    //             'datadetail' => $this->DetailbarangpinjamModel->findAll(),
    //             'dataLaporan' => $this->BarangpinjamModel->findAll()
    //         ];

    //         return view('barangpinjam/cetakberitaacara', $data);
    //     }
    // }

    public function listDataPinjamUser()
    {
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');

        $request = Services::request();
        $datamodel = new ModelDataBarangPinjam($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables($tglawal, $tglakhir);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $db = \Config\Database::connect();

                $jumlahData = $db->table('detail_barangpinjam')->where('detpeminjam', $list->id_peminjam)->countAllResults();

                $tomboldetail = " <button type=\"button\" class=\"btn btn-sm btn-warning\" id=\"detail\" 
                Title=\"Detail Item\" onclick=\"detail('" . $list->id_peminjam . "')\"><i class=\"fa fa-eye\"></i></button>";

                $row[] = $no;
                $row[] = $list->id_peminjam;
                $row[] = $list->tglpinjam;
                $row[] = $list->namasiswa;
                $row[] = $jumlahData;
                $row[] = $list->tglkembali;
                $row[] = ($list->status == 'P') ? "<span class=\"badge badge-info\">Dipinjam</span>" : "<span class=\"badge badge-success\">Kembali</span>";
                $row[] = $tomboldetail;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all($tglawal, $tglakhir),
                "recordsFiltered" => $datamodel->count_filtered($tglawal, $tglakhir),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }
}
