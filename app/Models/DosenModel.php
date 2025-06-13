<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table            = 'dosen';

    protected $allowedFields    = [
        'nidn',
        'nama_dosen',
        'jenis_kelamin',
        'no_hp',
        'alamat'
    ];

    public function detaildosen($id)
    {

        $db      = \Config\Database::connect();
        $builder = $db->table('dosen');
        $builder->select('dosen.*, users.username, users.status, users.role');
        $builder->join('users', 'users.id_dosen = dosen.id');
        $builder->where('dosen.id', $id);
        return $builder->get()->getRowArray();
    }

    public function editdosen($id)
    {

        $db      = \Config\Database::connect();
        $builder = $db->table('dosen');
        $builder->select('dosen.*, users.username, users.password, users.status, users.role');
        $builder->join('users', 'users.id_dosen = dosen.id');
        $builder->where('dosen.id', $id);
        return $builder->get()->getRowArray();
    }

    public function getJadwalNgajarByDosenId($id)
    {
        $builder = $this->db->table('dosen');

        $builder->select('
        dosen.nama_dosen,
        kelas.hari,
        kelas.ruangan,
        kelas.jam_masuk,
        kelas.jam_pulang,
        matkul.matkul AS nama_matkul
    ');

        // Join ke matkul berdasarkan nama dosen
        $builder->join('matkul', 'matkul.dosen_pengampu = dosen.nama_dosen', 'left');

        // Join ke kelas berdasarkan id_matkul dan id_dosen
        $builder->join('kelas', 'kelas.id_matkul = matkul.id', 'left');

        // Filter berdasarkan ID dosen
        $builder->where('dosen.id', $id);

        return $builder->get()->getResultArray();
    }
}
