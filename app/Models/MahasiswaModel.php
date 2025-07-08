<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table            = 'mahasiswa';
    protected $allowedFields    = [
        'nim',
        'nama',
        'jenis_kelamin',
        'alamat',
        'no_handphone',
        'semester',
        'jurusan',
        'foto',
        'lokasi_presensi',
        'nama_ortu',
        'jk_ortu',
        'nohp_ortu',
    ];

    public function detailmahasiswa($id)
    {

        $db      = \Config\Database::connect();
        $builder = $db->table('mahasiswa');
        $builder->select('mahasiswa.*, users.username, users.status, users.role');
        $builder->join('users', 'users.id_mahasiswa = mahasiswa.id');
        $builder->where('mahasiswa.id', $id);
        return $builder->get()->getRowArray();
    }

    public function editmahasiswa($id)
    {

        $db      = \Config\Database::connect();
        $builder = $db->table('mahasiswa');
        $builder->select('mahasiswa.*, users.username, users.password, users.status, users.role');
        $builder->join('users', 'users.id_mahasiswa = mahasiswa.id');
        $builder->where('mahasiswa.id', $id);
        return $builder->get()->getRowArray();
    }
}
