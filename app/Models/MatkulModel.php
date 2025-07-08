<?php

namespace App\Models;

use CodeIgniter\Model;

class MatkulModel extends Model
{
    protected $table            = 'matkul'; // Nama tabel di database
    protected $allowedFields    = [
        'matkul', // Field yang diizinkan untuk diisi
        'dosen_pengampu',
        'prodi_id',
    ];
}
