<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'username';
    protected $allowedFields    = [
        'username', 'usernama', 'userpassword', 'userlevelid', 'useraktif'
    ];
}
