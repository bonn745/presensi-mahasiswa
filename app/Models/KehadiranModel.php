<?php

namespace App\Models;

use CodeIgniter\Model;

class KehadiranModel extends Model
{
    protected $table            = 'kehadiran';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_pegawai',
        'keterangan',
        'tanggal',
        'deskripsi',
        'file',
        'status_pengajuan'
    ];

    public function getKehadiranWithPegawai()
    {
        return $this->db->table('kehadiran')
            ->select('kehadiran.*, pegawai.nip, pegawai.nama')
            ->join('pegawai', 'pegawai.id = kehadiran.id_pegawai')
            ->get()
            ->getResultArray();
    }
}
