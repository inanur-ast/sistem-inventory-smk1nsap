<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangpinjamModel extends Model
{
    protected $table            = 'barangpinjam';
    protected $primaryKey       = 'id_peminjam';
    protected $allowedFields    = [
        'id_peminjam', 'tglpinjam', 'idsiswa', 'tglkembali', 'status'
    ];

    public function generateCode()
    {
        $builder = $this->table('barangpinjam');
        $builder->selectMax('id_peminjam', 'kodeMax');
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            foreach ($query->getResult() as $key) {
                $kode = '';
                $today = date('ymd');
                $ambildata = substr($key->kodeMax, -4);
                $increment = intval($ambildata) + 1;
                $kode = sprintf('%04s', $increment);
            }
        } else {
            $kode = '004';
        }
        return 'PJM' . $today . $kode;
    }

    public function laporanPerPeriode($tgl_awal, $tgl_akhir)
    {
        return $this->table('barangpinjam')->join('datasiswa', 'idsiswa=nis')->where('tglpinjam >=', $tgl_awal)->where('tglpinjam <=', $tgl_akhir)->get();
    }
}
