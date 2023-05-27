<?php

namespace App\Models;

use CodeIgniter\Model;

class TempInventarisRuang extends Model
{
    protected $table            = 'temp_inventarisruang';
    protected $primaryKey       = 'iddetail';
    protected $allowedFields    = [
        'detinven', 'detbrgkode', 'detkondisi', 'detjml', 'detkatid'
    ];

    public function tampilDataTemp($inventaris)
    {
        return $this->table('temp_inventarisruang')->join('barang', 'detbrgkode=brgkode')->join('kondisi', 'detkondisi=konid')->where('detinven', $inventaris)->get();
    }
}
