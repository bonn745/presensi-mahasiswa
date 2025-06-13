<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $allowedFields    = [
        'id_mahasiswa',
        'id_dosen',
        'username',
        'password',
        'status',
        'role'
    ];
}
