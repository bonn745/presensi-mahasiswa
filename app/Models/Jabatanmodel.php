<?php

namespace App\Models;

use CodeIgniter\Model;

class JabatanModel extends Model
{
    protected $table            = 'jabatan'; // Nama tabel di database
    protected $allowedFields    = [
        'jabatan' // Field yang diizinkan untuk diisi
    ];
}
