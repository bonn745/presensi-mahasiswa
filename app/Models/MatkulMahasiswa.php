<?php

namespace App\Models;

use CodeIgniter\Model;

class MatkulMahasiswa extends Model
{
    protected $table            = 'matkul_mahasiswa';
    protected $allowedFields    = [
        'matkul_id',
        'mhs_id'
    ];
}
