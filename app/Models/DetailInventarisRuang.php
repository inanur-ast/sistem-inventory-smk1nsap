<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailInventarisRuang extends Model
{

    protected $table            = 'detail_inventarisruang';
    protected $primaryKey       = 'iddetail';
    protected $allowedFields    = [
        'detinven', 'detbrgkode', 'detkondisi', 'detjml', 'detkatid'
    ];

    public function dataDetail($id)
    {
        return $this->table('detail_inventarisruang')->join('barang', 'brgkode=detbrgkode')->join('kondisi', 'konid=detkondisi')->where('detinven', $id)->get();
    }

    public function ambilDetailId($iddetail)
    {
        return $this->table('detail_inventarisruang')->join('barang', 'brgkode=detbrgkode')->join('kondisi', 'konid=detkondisi')->where('iddetail', $iddetail)->get();
    }

    public function ambilTotalBarang($id)
    {
        $query =  $this->table('detail_inventarisruang')->getwhere([
            'detinven' => $id
        ]);

        $totalbarang = 0;
        foreach ($query->getResultArray() as $r) {
            $totalbarang += $r['detjml'];
        }

        return $totalbarang;
    }

    public function laporanDetailPerPeriode($tgl_awal, $tgl_akhir)
    {
        return $this->table('detail_inventarisruang')->join('barang', 'brgkode=detbrgkode')->join('kondisi', 'konid=detkondisi')
            ->join('inventaris_ruang', 'id=detinven')->where('tanggal >=', $tgl_awal)->where('tanggal <=', $tgl_akhir)->get();
    }
}
