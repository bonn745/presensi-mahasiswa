<?php

namespace App\Models;

use CodeIgniter\Model;

class UangKuliah extends Model
{
    protected $table            = 'uang_kuliah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'npm',
        'jenis_pembayaran',
    ];
}
