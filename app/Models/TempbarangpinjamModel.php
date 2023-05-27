<?php

namespace App\Models;

use CodeIgniter\Model;

class TempbarangpinjamModel extends Model
{
    protected $table            = 'temp_barangpinjam';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'detpeminjam', 'detkdbrg', 'detjumlah', 'detkatid', 'detkondisi'
    ];

    public function tampilDataTemp($nopeminjam)
    {
        return $this->table('temp_barangpinjam')->join('barang', 'detkdbrg=brgkode')->join('kondisi', 'detkondisi=konid')->where('detpeminjam', $nopeminjam)->get();
    }
}
