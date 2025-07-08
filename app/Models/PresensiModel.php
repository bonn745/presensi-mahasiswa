<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiModel extends Model
{
    protected $table            = 'presensi';
    protected $allowedFields    = [
        'id_mahasiswa',
        'tanggal_masuk',
        'jam_masuk',
        'foto_masuk',
        'id_lokasi_presensi',
        'tanggal_keluar',
        'jam_keluar',
        'foto_keluar',
        'id_lokasi_presensi_keluar',
        'id_matkul'
    ];

    public function rekap_harian()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('presensi');
        $builder->select('
        presensi.*, 
        mahasiswa.nama, 
        kelas.jam_masuk as jam_masuk_kampus, 
        kelas.jam_pulang as jam_pulang_kampus,
        matkul.matkul as nama_matkul
    ');
        $builder->join('mahasiswa', 'mahasiswa.id = presensi.id_mahasiswa');
        $builder->join('kelas', 'kelas.id_matkul = presensi.id_matkul');
        $builder->join('matkul', 'matkul.id = presensi.id_matkul');
        $builder->where('tanggal_masuk', date('Y-m-d'));
        $builder->orderBy('presensi.tanggal_masuk', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function rekap_harian_filter($filter_tanggal,$filter_matkul = null)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('presensi');
        $builder->select('
        presensi.*, 
        mahasiswa.nama, 
        kelas.jam_masuk as jam_masuk_kampus, 
        kelas.jam_pulang as jam_pulang_kampus,
        matkul.matkul as nama_matkul
    ');
        $builder->join('mahasiswa', 'mahasiswa.id = presensi.id_mahasiswa');
        $builder->join('kelas', 'kelas.id_matkul = presensi.id_matkul');
        $builder->join('matkul', 'matkul.id = presensi.id_matkul');
        $builder->where('tanggal_masuk', $filter_tanggal);
        if(!is_null($filter_matkul)) $builder->where('matkul.id', $filter_matkul);
        $builder->orderBy('presensi.tanggal_masuk', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function rekap_bulanan()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('presensi');
        $builder->select('
        presensi.*, 
        mahasiswa.nama, 
        kelas.jam_masuk as jam_masuk_kampus, 
        kelas.jam_pulang as jam_pulang_kampus,
        matkul.matkul as nama_matkul
    ');
        $builder->join('mahasiswa', 'mahasiswa.id = presensi.id_mahasiswa');
        $builder->join('kelas', 'kelas.id_matkul = presensi.id_matkul');
        $builder->join('matkul', 'matkul.id = presensi.id_matkul');
        $builder->where('MONTH(tanggal_masuk)', date('m'));
        $builder->where('YEAR(tanggal_masuk)', date('Y'));
        $builder->orderBy('presensi.tanggal_masuk', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function rekap_bulanan_filter($filter_bulan, $filter_tahun)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('presensi');
        $builder->select('
        presensi.*, 
        mahasiswa.nama, 
        kelas.jam_masuk as jam_masuk_kampus, 
        kelas.jam_pulang as jam_pulang_kampus,
        matkul.matkul as nama_matkul
    ');
        $builder->join('mahasiswa', 'mahasiswa.id = presensi.id_mahasiswa');
        $builder->join('kelas', 'kelas.id_matkul = presensi.id_matkul');
        $builder->join('matkul', 'matkul.id = presensi.id_matkul');
        $builder->where('MONTH(tanggal_masuk)', $filter_bulan);
        $builder->where('YEAR(tanggal_masuk)', $filter_tahun);
        $builder->orderBy('presensi.tanggal_masuk', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function rekap_presensi_mahasiswa()
    {
        return $this->select('presensi.*, mahasiswa.nama, kelas.jam_masuk as jam_masuk_kampus, kelas.jam_pulang as jam_pulang_kampus, matkul.matkul as nama_matkul')
            ->join('mahasiswa', 'mahasiswa.id = presensi.id_mahasiswa')
            ->join('kelas', 'kelas.id_matkul = presensi.id_matkul')
            ->join('matkul', 'matkul.id = presensi.id_matkul')
            ->where('presensi.id_mahasiswa', session()->get('id_mahasiswa'))
            ->where('DATE(presensi.tanggal_masuk)', date('Y-m-d'))
            ->orderBy('presensi.tanggal_masuk', 'DESC')
            ->findAll();
    }

    public function rekap_presensi_mahasiswa_filter($tanggal)
    {
        return $this->select('presensi.*, mahasiswa.nama, kelas.jam_masuk as jam_masuk_kampus, kelas.jam_pulang as jam_pulang_kampus, matkul.matkul as nama_matkul')
            ->join('mahasiswa', 'mahasiswa.id = presensi.id_mahasiswa')
            ->join('kelas', 'kelas.id_matkul = presensi.id_matkul')
            ->join('matkul', 'matkul.id = presensi.id_matkul')
            ->where('presensi.id_mahasiswa', session()->get('id_mahasiswa'))
            ->where('DATE(presensi.tanggal_masuk)', $tanggal)
            ->orderBy('presensi.tanggal_masuk', 'DESC')
            ->findAll();
    }
}
