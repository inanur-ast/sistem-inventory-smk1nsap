<?php

namespace App\Models;

use CodeIgniter\Model;

class KondisiModel extends Model
{
    protected $table            = 'kondisi';
    protected $primaryKey       = 'konid';
    protected $allowedFields    = ['konid', 'konnama'];
}
