<?php

namespace App\Models;

use CodeIgniter\Model;

class DatasiswaModel extends Model
{
    protected $table            = 'datasiswa';
    protected $primaryKey       = 'nis';
    protected $allowedFields    = [
        'nis', 'namasiswa', 'kelas'
    ];

    public function ambilDataTerakhir()
    {
        return $this->table('datasiswa')->join('kelas', 'id=kelas')->limit(1)->orderBy('nis', 'DESC')->get();
    }
}
