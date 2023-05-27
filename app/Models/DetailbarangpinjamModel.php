<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailbarangpinjamModel extends Model
{
    protected $table            = 'detail_barangpinjam';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'detpeminjam', 'detkdbrg', 'detjumlah', 'detkatid', 'detkondisi'
    ];

    public function tampilDataTemp($nopeminjam)
    {
        return $this->table('detail_barangpinjam')->join('barang', 'detkdbrg=brgkode')->join('kondisi', 'detkondisi=konid')->where('detpeminjam', $nopeminjam)->get();
    }

    public function laporanDetailPerPeriode($tgl_awal, $tgl_akhir)
    {
        return $this->table('detail_barangpinjam')->join('barang', 'detkdbrg=brgkode')->join('barangpinjam', 'detpeminjam=id_peminjam')->join('kondisi', 'detkondisi=konid')->where('tglpinjam >=', $tgl_awal)->where('tglpinjam <=', $tgl_akhir)->get();
    }

    public function ambilDetailId($id)
    {
        return $this->table('detail_barangpinjam')->join('barang', 'detkdbrg=brgkode')->where('id', $id)->get();
    }
}
