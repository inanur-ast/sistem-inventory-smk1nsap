<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DatasiswaModel;
use App\Models\KelasModel;
use App\Models\ModelDataSiswa;
use Config\Services;

class Datasiswa extends BaseController
{
    protected $DatasiswaModel;
    protected $kelasModel;

    public function __construct()
    {
        $this->DatasiswaModel = new DatasiswaModel();
        $this->kelasModel = new KelasModel();
    }
    public function formtambah()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'datakelas' => $this->kelasModel->findAll()
            ];

            $json = [
                'data' => view('datasiswa/modaltambah', $data)

            ];
            echo json_encode($json);
        }
    }

    public function simpan()
    {
        $nis = $this->request->getPost('nis');
        $nmsiswa = $this->request->getPost('nmsiswa');
        $kelas = $this->request->getPost('kelas');

        $validation = \config\Services::validation();

        $valid = $this->validate([
            'nis' => [
                'rules' => 'required',
                'label' => 'Nis',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'nmsiswa' => [
                'rules' => 'required',
                'label' => 'Nama Siswa',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'kelas' => [
                'rules' => 'required',
                'label' => 'Kelas',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]
        ]);

        if (!$valid) {
            $json = [
                'error' => [
                    'errnis' => $validation->getError('nis'),
                    'errNamaSiswa' => $validation->getError('nmsiswa'),
                    'errKelas' => $validation->getError('kelas')
                ]
            ];
        } else {
            $this->DatasiswaModel->insert([
                'nis' => $nis,
                'namasiswa' => $nmsiswa,
                'kelas' => $kelas
            ]);

            $rowData = $this->DatasiswaModel->ambilDataTerakhir()->getRowArray();

            $json = [
                'sukses' => "Data siswa berhasil disimpan?",
                'namasiswa' => $rowData['namasiswa'],
                'nis' => $rowData['nis']
            ];
        }

        echo json_encode($json);
    }

    public function modalData()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('datasiswa/modaldata')
            ];
            echo json_encode($json);
        }
    }

    public function listData()
    {
        $request = Services::request();
        $datamodel = new ModelDataSiswa($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $row = [];

                $tombolpilih = " <button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"pilih('" . $list->nis . "','" . $list->namasiswa . "')\">Pilih</button>";
                $tombolhapus = " <button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"hapus('" . $list->nis . "','" . $list->namasiswa . "')\">Hapus</button>";

                $row[] = $list->nis;
                $row[] = $list->namasiswa;
                $row[] = $list->namakelas;
                $row[] = $tombolpilih . " " . $tombolhapus;
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

    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $nis = $this->request->getPost('nis');

            $this->DatasiswaModel->delete($nis);
            $json = [
                'sukses' => 'Data siswa berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }
}
