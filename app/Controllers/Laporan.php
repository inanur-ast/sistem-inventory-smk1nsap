<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangmasukModel;
use App\Models\BarangModel;
use App\Models\BarangpinjamModel;
use App\Models\DetailbarangmasukModel;
use App\Models\DetailbarangpinjamModel;
use App\Models\DetailInventarisRuang;
use App\Models\InventarisRuangModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;  //folder vendor/phpoffice

class Laporan extends BaseController
{
    protected $BarangMasukModel;
    protected $DetailBarangMasukModel;
    protected $BarangPinjamModel;
    protected $DetailBarangPinjamModel;
    protected $BarangModel;
    protected $DetailInventarisRuangModel;
    protected $InventarisRuang;

    public function __construct()
    {
        $this->BarangMasukModel = new BarangmasukModel();
        $this->DetailBarangMasukModel = new DetailbarangmasukModel();
        $this->BarangPinjamModel = new BarangpinjamModel();
        $this->DetailBarangPinjamModel = new DetailbarangpinjamModel();
        $this->BarangModel = new BarangModel();
        $this->DetailInventarisRuangModel = new DetailInventarisRuang();
        $this->InventarisRuang =  new InventarisRuangModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Cetak laporan'
        ];
        return view('laporan/index', $data);
    }

    public function cetak_barang_masuk()
    {
        $data = [
            'title' => 'Cetak laporan'
        ];
        return view('laporan/cetakbarangmasuk', $data);
    }

    public function cetak_barang_masuk_periode()
    {
        $tombolCetak = $this->request->getPost('btnCetak');
        $tombolExport = $this->request->getPost('btnExport');
        $tgl_awal = $this->request->getPost('tgl_awal');
        $tgl_akhir = $this->request->getPost('tgl_akhir');

        $dataLaporan = $this->BarangMasukModel->laporanPerPeriode($tgl_awal, $tgl_akhir);
        $dataLaporanDetail = $this->DetailBarangMasukModel->laporanDetailPerPeriode($tgl_awal, $tgl_akhir);

        if (isset($tombolCetak)) {
            $data = [
                'dataLaporan' => $dataLaporan,
                'tgl_awal' => $tgl_awal,
                'tgl_akhir' => $tgl_akhir,
                'dataDetailLaporan' => $dataLaporanDetail
            ];

            return view('laporan/cetakLaporanBarangMasuk', $data);
        }
        if (isset($tombolExport)) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('E1', "DATA BARANG MASUK");
            $sheet->mergeCells('E1:K1');
            $sheet->getStyle('E1')->getFont()->setBold(true);

            $sheet->setCellValue('B2', "Alamat: JL. Wonosari-Panggang, Km 22, Saptosari, Gunung Kidul,
                                Kepek, Kec. Saptosari, Kabupaten Gunung Kidul, Daerah Istimewa Yogyakarta 55871");
            $sheet->mergeCells('B2:K3');
            $sheet->getStyle('B2')->getFont()->setBold(true);

            $styleColumn = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];

            $borderArray = [
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ]
                ],
            ];

            $sheet->setCellValue('A4', "No");
            $sheet->setCellValue('B4', "No.Faktur");
            $sheet->setCellValue('C4', "Tanggal");
            $sheet->setCellValue('D4', "Total Harga");

            $sheet->getStyle('A4')->applyFromArray($styleColumn);
            $sheet->getStyle('B4')->applyFromArray($styleColumn);
            $sheet->getStyle('C4')->applyFromArray($styleColumn);
            $sheet->getStyle('D4')->applyFromArray($styleColumn);

            $sheet->getStyle('A4')->applyFromArray($borderArray);
            $sheet->getStyle('B4')->applyFromArray($borderArray);
            $sheet->getStyle('C4')->applyFromArray($borderArray);
            $sheet->getStyle('D4')->applyFromArray($borderArray);

            $no = 1;
            $numRow = 4;

            foreach ($dataLaporan->getResultArray() as $row) :

                $sheet->setCellValue('A' . $numRow, $no);
                $sheet->setCellValue('B' . $numRow, $row['faktur']);
                $sheet->setCellValue('C' . $numRow, $row['tglfaktur']);
                $sheet->setCellValue('D' . $numRow, $row['totalharga']);

                $sheet->getStyle('A' . $numRow)->applyFromArray($styleColumn);

                $sheet->getStyle('A' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('B' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('C' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('D' . $numRow)->applyFromArray($borderArray);

                $no++;
                $numRow++;
            endforeach;

            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->setTitle("Laporan Barang Masuk");

            header('Content-Type : application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename = "BarangMasuk.xlsx"');
            header('Cache-Control:max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }
    }

    public function cetak_barang_pinjam()
    {
        $data = [
            'title' => 'Cetak laporan'
        ];
        return view('laporan/cetakbarangpinjam', $data);
    }

    public function cetak_barang_pinjam_periode()
    {
        $tombolCetak = $this->request->getPost('btnCetak');
        $tombolExport = $this->request->getPost('btnExport');
        $tgl_awal = $this->request->getPost('tgl_awal');
        $tgl_akhir = $this->request->getPost('tgl_akhir');


        $dataLaporan = $this->BarangPinjamModel->laporanPerPeriode($tgl_awal, $tgl_akhir);
        $dataLaporanDetail = $this->DetailBarangPinjamModel->laporanDetailPerPeriode($tgl_awal, $tgl_akhir);

        if (isset($tombolCetak)) {
            $data = [
                'dataLaporan' => $dataLaporan,
                'tgl_awal' => $tgl_awal,
                'tgl_akhir' => $tgl_akhir,
                'dataDetailLaporan' => $dataLaporanDetail,
            ];

            return view('laporan/cetakLaporanBarangPinjam', $data);
        }
        if (isset($tombolExport)) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('E1', "DATA BARANG PINJAM");
            $sheet->mergeCells('E1:K1');
            $sheet->getStyle('E1')->getFont()->setBold(true);

            $sheet->setCellValue('B2', "Alamat: JL. Wonosari-Panggang, Km 22, Saptosari, Gunung Kidul,
                                Kepek, Kec. Saptosari, Kabupaten Gunung Kidul, Daerah Istimewa Yogyakarta 55871");
            $sheet->mergeCells('B2:K3');
            $sheet->getStyle('B2')->getFont()->setBold(true);

            $styleColumn = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];

            $borderArray = [
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ]
                ],
            ];

            $sheet->setCellValue('A4', "No");
            $sheet->setCellValue('B4', "No.Peminjaman");
            $sheet->setCellValue('C4', "Id siswa");
            $sheet->setCellValue('D4', "Kode Barang");
            $sheet->setCellValue('E4', "Nama Barang");
            $sheet->setCellValue('F4', "Tanggal Pinjam");
            $sheet->setCellValue('G4', "Tanggal Kembali");
            $sheet->setCellValue('H4', "Kondisi Barang");
            $sheet->setCellValue('I4', "Jumlah");
            $sheet->setCellValue('J4', "Status");

            $sheet->getStyle('A4')->applyFromArray($styleColumn);
            $sheet->getStyle('B4')->applyFromArray($styleColumn);
            $sheet->getStyle('C4')->applyFromArray($styleColumn);
            $sheet->getStyle('D4')->applyFromArray($styleColumn);
            $sheet->getStyle('E4')->applyFromArray($styleColumn);
            $sheet->getStyle('F4')->applyFromArray($styleColumn);
            $sheet->getStyle('G4')->applyFromArray($styleColumn);
            $sheet->getStyle('H4')->applyFromArray($styleColumn);
            $sheet->getStyle('I4')->applyFromArray($styleColumn);
            $sheet->getStyle('J4')->applyFromArray($styleColumn);

            $sheet->getStyle('A4')->applyFromArray($borderArray);
            $sheet->getStyle('B4')->applyFromArray($borderArray);
            $sheet->getStyle('C4')->applyFromArray($borderArray);
            $sheet->getStyle('D4')->applyFromArray($borderArray);
            $sheet->getStyle('E4')->applyFromArray($borderArray);
            $sheet->getStyle('F4')->applyFromArray($borderArray);
            $sheet->getStyle('G4')->applyFromArray($borderArray);
            $sheet->getStyle('H4')->applyFromArray($borderArray);
            $sheet->getStyle('I4')->applyFromArray($borderArray);
            $sheet->getStyle('J4')->applyFromArray($borderArray);

            $no = 1;
            $numRow = 11;

            foreach ($dataLaporanDetail->getResultArray() as $row) :

                $sheet->setCellValue('A' . $numRow, $no);
                $sheet->setCellValue('B' . $numRow, $row['detpeminjam']);
                $sheet->setCellValue('C' . $numRow, $row['idsiswa']);
                $sheet->setCellValue('D' . $numRow, $row['detkdbrg']);
                $sheet->setCellValue('E' . $numRow, $row['brgnama']);
                $sheet->setCellValue('F' . $numRow, $row['tglpinjam']);
                $sheet->setCellValue('G' . $numRow, $row['tglkembali']);
                $sheet->setCellValue('H' . $numRow, $row['konnama']);
                $sheet->setCellValue('I' . $numRow, $row['detjumlah']);
                $sheet->setCellValue('J' . $numRow, $row['status']);

                $sheet->getStyle('A' . $numRow)->applyFromArray($styleColumn);

                $sheet->getStyle('A' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('B' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('C' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('D' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('E' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('F' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('G' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('H' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('I' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('J' . $numRow)->applyFromArray($borderArray);

                $no++;
                $numRow++;
            endforeach;

            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->setTitle("Laporan Barang Pinjam");

            header('Content-Type : application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename = "BarangPinjam.xlsx"');
            header('Cache-Control:max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }
    }

    public function cetak_barang()
    {
        $data = [
            'title' => 'Cetak laporan Barang'
        ];
        return view('laporan/cetakbarang', $data);
    }

    public function cetak_barang_semua()
    {
        $tombolCetak = $this->request->getPost('btnCetak');
        $tombolExport = $this->request->getPost('btnExport');

        $dataLaporan = $this->BarangModel->tampildata();

        if (isset($tombolCetak)) {
            $data = [
                'dataLaporan' => $dataLaporan
            ];

            return view('laporan/cetakLaporanBarang', $data);
        }
        if (isset($tombolExport)) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('E1', "DATA BARANG KESELURUHAN");
            $sheet->mergeCells('E1:K1');
            $sheet->getStyle('E1')->getFont()->setBold(true);

            $sheet->setCellValue('B2', "Alamat: JL. Wonosari-Panggang, Km 22, Saptosari, Gunung Kidul,
                                Kepek, Kec. Saptosari, Kabupaten Gunung Kidul, Daerah Istimewa Yogyakarta 55871");
            $sheet->mergeCells('B2:K3');
            $sheet->getStyle('B2')->getFont()->setBold(true);

            $styleColumn = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];

            $borderArray = [
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ]
                ],
            ];

            $sheet->setCellValue('A4', "No");
            $sheet->setCellValue('B4', "Kode Barang");
            $sheet->setCellValue('C4', "Nama Barang");
            $sheet->setCellValue('D4', "Kategori");
            $sheet->setCellValue('E4', "Satuan");
            $sheet->setCellValue('F4', "Stok");
            $sheet->setCellValue('G4', "Spesifikasi");

            $sheet->getStyle('A4')->applyFromArray($styleColumn);
            $sheet->getStyle('B4')->applyFromArray($styleColumn);
            $sheet->getStyle('C4')->applyFromArray($styleColumn);
            $sheet->getStyle('D4')->applyFromArray($styleColumn);
            $sheet->getStyle('E4')->applyFromArray($styleColumn);
            $sheet->getStyle('F4')->applyFromArray($styleColumn);
            $sheet->getStyle('G4')->applyFromArray($styleColumn);

            $sheet->getStyle('A4')->applyFromArray($borderArray);
            $sheet->getStyle('B4')->applyFromArray($borderArray);
            $sheet->getStyle('C4')->applyFromArray($borderArray);
            $sheet->getStyle('D4')->applyFromArray($borderArray);
            $sheet->getStyle('E4')->applyFromArray($borderArray);
            $sheet->getStyle('F4')->applyFromArray($borderArray);
            $sheet->getStyle('G4')->applyFromArray($borderArray);

            $no = 1;
            $numRow = 7;

            foreach ($dataLaporan->getResultArray() as $row) :

                $sheet->setCellValue('A' . $numRow, $no);
                $sheet->setCellValue('B' . $numRow, $row['brgkode']);
                $sheet->setCellValue('C' . $numRow, $row['brgnama']);
                $sheet->setCellValue('D' . $numRow, $row['katnama']);
                $sheet->setCellValue('E' . $numRow, $row['satnama']);
                $sheet->setCellValue('F' . $numRow, $row['brgstok']);
                $sheet->setCellValue('G' . $numRow, $row['spesifikasi']);

                $sheet->getStyle('A' . $numRow)->applyFromArray($styleColumn);

                $sheet->getStyle('A' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('B' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('C' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('D' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('E' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('F' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('G' . $numRow)->applyFromArray($borderArray);

                $no++;
                $numRow++;
            endforeach;

            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->setTitle("Laporan Barang");

            header('Content-Type : application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename = "Laporan Barang.xlsx"');
            header('Cache-Control:max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }
    }

    public function cetak_inventaris_ruang()
    {
        $data = [
            'title' => 'Cetak laporan Inventaris Ruang'
        ];
        return view('laporan/cetakinventarisruang', $data);
    }

    public function cetak_inventarisruang_periode()
    {
        $tombolCetak = $this->request->getPost('btnCetak');
        $tombolExport = $this->request->getPost('btnExport');
        $tgl_awal = $this->request->getPost('tgl_awal');
        $tgl_akhir = $this->request->getPost('tgl_akhir');

        $dataLaporan = $this->InventarisRuang->laporanPerPeriode($tgl_awal, $tgl_akhir);
        $dataLaporanDetail = $this->DetailInventarisRuangModel->laporanDetailPerPeriode($tgl_awal, $tgl_akhir);

        if (isset($tombolCetak)) {
            $data = [
                'dataLaporan' => $dataLaporan,
                'tgl_awal' => $tgl_awal,
                'tgl_akhir' => $tgl_akhir,
                'dataDetailLaporan' => $dataLaporanDetail
            ];

            return view('laporan/cetakLaporanInventarisruang', $data);
        }
        if (isset($tombolExport)) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('E1', "DATA INVENTARIS RUANG");
            $sheet->mergeCells('E1:K1');
            $sheet->getStyle('E1')->getFont()->setBold(true);

            $sheet->setCellValue('B2', "Alamat: JL. Wonosari-Panggang, Km 22, Saptosari, Gunung Kidul,
                                Kepek, Kec. Saptosari, Kabupaten Gunung Kidul, Daerah Istimewa Yogyakarta 55871");
            $sheet->mergeCells('B2:K3');
            $sheet->getStyle('B2')->getFont()->setBold(true);



            $styleColumn = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];

            $borderArray = [
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ]
                ],
            ];

            $sheet->setCellValue('A4', "No");
            $sheet->setCellValue('B4', "Ruangan");
            $sheet->setCellValue('C4', "Kode Barang");
            $sheet->setCellValue('D4', "Nama Barang");
            $sheet->setCellValue('E4', "Kategori");
            $sheet->setCellValue('F4', "Kondisi");
            $sheet->setCellValue('G4', "Jumlah");

            $sheet->getStyle('A4')->applyFromArray($styleColumn);
            $sheet->getStyle('B4')->applyFromArray($styleColumn);
            $sheet->getStyle('C4')->applyFromArray($styleColumn);
            $sheet->getStyle('D4')->applyFromArray($styleColumn);
            $sheet->getStyle('E4')->applyFromArray($styleColumn);
            $sheet->getStyle('F4')->applyFromArray($styleColumn);
            $sheet->getStyle('G4')->applyFromArray($styleColumn);

            $sheet->getStyle('A4')->applyFromArray($borderArray);
            $sheet->getStyle('B4')->applyFromArray($borderArray);
            $sheet->getStyle('C4')->applyFromArray($borderArray);
            $sheet->getStyle('D4')->applyFromArray($borderArray);
            $sheet->getStyle('E4')->applyFromArray($borderArray);
            $sheet->getStyle('F4')->applyFromArray($borderArray);
            $sheet->getStyle('G4')->applyFromArray($borderArray);

            $no = 1;
            $numRow = 8;

            foreach ($dataLaporanDetail->getResultArray() as $row) :

                $sheet->setCellValue('A' . $numRow, $no);
                $sheet->setCellValue('B' . $numRow, $row['detinven']);
                $sheet->setCellValue('C' . $numRow, $row['detbrgkode']);
                $sheet->setCellValue('D' . $numRow, $row['brgnama']);
                $sheet->setCellValue('E' . $numRow, $row['detkatid']);
                $sheet->setCellValue('F' . $numRow, $row['konnama']);
                $sheet->setCellValue('G' . $numRow, $row['detjml']);

                $sheet->getStyle('A' . $numRow)->applyFromArray($styleColumn);

                $sheet->getStyle('A' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('B' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('C' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('D' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('E' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('F' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('G' . $numRow)->applyFromArray($borderArray);

                $no++;
                $numRow++;
            endforeach;

            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->setTitle("Laporan Inventaris Ruang");

            header('Content-Type : application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename = "LaporanInventarisRuang.xlsx"');
            header('Cache-Control:max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }
    }
}
