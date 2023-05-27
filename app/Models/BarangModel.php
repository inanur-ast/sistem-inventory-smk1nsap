<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'brgkode';

    protected $allowedFields    = [
        'brgkode', 'brgnama', 'brgkatid', 'brgsatid', 'brgstok', 'spesifikasi'
    ];

    public function tampildata()
    {
        return $this->table('barang')->join('kategori', 'brgkatid=katid')->join('satuan', 'brgsatid=satid')->get();
    }

    public function generateCode()
    {
        $builder = $this->table('barang');
        $builder->selectMax('brgkode', 'kodeMax');
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            foreach ($query->getResult() as $key) {
                $kd = '';
                $today = date('ymd');
                $ambildata = substr($key->kodeMax, -3);
                $increment = intval($ambildata) + 1;
                $kd = sprintf('%03s', $increment);
            }
        } else {
            $kd = '001';
        }
        return 'PROD' . $today . $kd;
    }

    public function tampildata_cari($cari)
    {
        return $this->table('barang')->join('kategori', 'brgkatid=katid')->join('satuan', 'brgsatid=satid')->orlike('brgkode', $cari)->orlike('brgnama', $cari);
    }
}
