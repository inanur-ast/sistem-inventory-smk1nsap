<?php

namespace App\Models;

use CodeIgniter\Model;

class InventarisRuangModel extends Model
{
    protected $table            = 'inventaris_ruang';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id', 'tanggal', 'totalbarang'
    ];

    public function tampildata()
    {
        return $this->table('inventaris_ruang')->get();
    }

    public function cekFaktur($id)
    {
        return $this->table('inventaris_ruang')->getwhere([
            'sha1(id)' => $id
        ]);
    }

    public function laporanPerPeriode($tgl_awal, $tgl_akhir)
    {
        return $this->table('inventaris_ruang')->where('tanggal >=', $tgl_awal)->where('tanggal <=', $tgl_akhir)->get();
    }
}
