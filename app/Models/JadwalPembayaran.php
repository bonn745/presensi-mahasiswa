<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalPembayaran extends Model
{
    protected $table            = 'jadwal_pembayaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'tanggal_pembayaran_tahap_1',
        'tanggal_pembayaran_tahap_2',
        'tanggal_pembayaran_tahap_3',
        'tanggal_notifikasi_tahap_1',
        'tanggal_notifikasi_tahap_2',
        'tanggal_notifikasi_tahap_3',
        'jam_notifikasi_tahap_1',
        'jam_notifikasi_tahap_2',
        'jam_notifikasi_tahap_3',
        'status_notifikasi_tahap_1',
        'status_notifikasi_tahap_2',
        'status_notifikasi_tahap_3',
        'status_jadwal',
    ];
}
