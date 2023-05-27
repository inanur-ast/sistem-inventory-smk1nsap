<?php

namespace App\Models;

use CodeIgniter\Model;

class TJmlbarangModel extends Model
{
    protected $table            = 'tjmlbarang';
    protected $primaryKey       = 'idjml';
    protected $allowedFields    = ['idjml', 'idkondisi', 'idbarang', 'jmlkondisi'];
}
