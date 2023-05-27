<?php

namespace App\Models;

use CodeIgniter\Model;

class DatapeminjamanbarangModel extends Model
{
    protected $table            = 'data_barang_peminjaman';
    protected $primaryKey       = 'pid';
    protected $allowedFields    = [
        'piddetail', 'pstok'
    ];
}
