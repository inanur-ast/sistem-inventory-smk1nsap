<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\KondisiModel;

class Kondisi extends BaseController
{

    protected $barang;
    protected $kondisi;

    public function __construct()
    {
        $this->barang = new BarangModel();
        $this->kondisi = new KondisiModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Form Tambah Kondisi Barang',
            'databarang' => $this->barang->findAll(),
        ];
        return view('kondisibarang/formtambah', $data);
    }

    public function formtambah()
    {
        $data = [
            'title' => 'Form Tambah Kondisi Barang',
            'databarang' => $this->barang->findAll(),
        ];
        return view('kondisibarang/formtambah', $data);
    }

    public function auto()
    {
        $data =  $this->kondisi->generateCode();
        return json_encode($data);
    }
}
