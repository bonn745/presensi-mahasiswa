<?php

namespace App\Models;

use CodeIgniter\Model;

class LogNotifikasiPresensiModel extends Model
{
    protected $table            = 'log_notifikasi_rekap_presensi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'tanggal',
        'status',
    ];
}
