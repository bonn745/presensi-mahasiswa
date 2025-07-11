<?php

namespace App\Models;

use CodeIgniter\Model;

class KetidakhadiranModel extends Model
{
    protected $table            = 'ketidakhadiran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_mahasiswa',
        'keterangan',
        'tanggal',
        'id_matkul',
        'deskripsi',
        'file',
        'status_pengajuan'
    ];

    public function getKetidakhadiranWithMahasiswa()
    {
        return $this->db->table('ketidakhadiran')
            ->select('ketidakhadiran.*, mahasiswa.nim, mahasiswa.nama')
            ->join('mahasiswa', 'mahasiswa.id = ketidakhadiran.id_mahasiswa')
            ->get()
            ->getResultArray();
    }

    public function getApprovedKetidakhadiranByDate($tanggal)
    {
        return $this->db->table('ketidakhadiran')
            ->select('ketidakhadiran.*, mahasiswa.nim, mahasiswa.nama')
            ->join('mahasiswa', 'mahasiswa.id = ketidakhadiran.id_mahasiswa')
            ->where('ketidakhadiran.tanggal', $tanggal)
            ->where('ketidakhadiran.status_pengajuan', 'Approved')
            ->get()
            ->getResultArray();
    }
}
