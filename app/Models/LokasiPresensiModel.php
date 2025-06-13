<?php

namespace App\Models;

use CodeIgniter\Model;

class LokasiPresensiModel extends Model
{
    protected $table            = 'lokasi_presensi';
    protected $allowedFields    = [
        'nama_ruangan',
        'alamat_lokasi',
        'tipe_lokasi',
        'jadwal_kuliah',
        'matkul',
        'latitude',
        'longitude',
        'radius',
        'zona_waktu',
        'jam_masuk',
        'jam_pulang'
    ];
}
