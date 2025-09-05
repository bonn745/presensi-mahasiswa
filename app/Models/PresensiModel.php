<?php

namespace App\Models;

use Carbon\Carbon;
use CodeIgniter\Model;

class PresensiModel extends Model
{
    protected $table            = 'presensi';
    protected $allowedFields    = [
        'id_mahasiswa',
        'tanggal',
        'jam_masuk',
        'foto_masuk',
        'id_lokasi_presensi',
        'jam_keluar',
        'foto_keluar',
        'id_lokasi_presensi_keluar',
        'id_matkul',
        'pertemuan_ke'
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
        $builder->where('tanggal', date('Y-m-d'));
        $builder->orderBy('presensi.tanggal', 'DESC');
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
        $builder->where('tanggal', $filter_tanggal);
        if(!is_null($filter_matkul)) $builder->where('matkul.id', $filter_matkul);
        $builder->orderBy('presensi.tanggal', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function rekap_bulanan()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('presensi');
        $builder->select('
        presensi.*, 
        mahasiswa.npm, 
        mahasiswa.nama, 
        kelas.jam_masuk as jam_masuk_kampus, 
        kelas.jam_pulang as jam_pulang_kampus,
        matkul.matkul as nama_matkul
    ');
        $builder->join('mahasiswa', 'mahasiswa.id = presensi.id_mahasiswa');
        $builder->join('kelas', 'kelas.id_matkul = presensi.id_matkul');
        $builder->join('matkul', 'matkul.id = presensi.id_matkul');
        $builder->where('MONTH(tanggal)', '08');
        $builder->where('YEAR(tanggal)', date('Y'));
        $builder->orderBy('presensi.tanggal', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function rekap_bulanan_filter($filter_bulan, $filter_tahun)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('presensi');
        $builder->select('
        presensi.*, 
        mahasiswa.npm, 
        mahasiswa.nama, 
        kelas.jam_masuk as jam_masuk_kampus, 
        kelas.jam_pulang as jam_pulang_kampus,
        matkul.matkul as nama_matkul
    ');
        $builder->join('mahasiswa', 'mahasiswa.id = presensi.id_mahasiswa');
        $builder->join('kelas', 'kelas.id_matkul = presensi.id_matkul');
        $builder->join('matkul', 'matkul.id = presensi.id_matkul');
        $builder->where('MONTH(tanggal)', $filter_bulan);
        $builder->where('YEAR(tanggal)', $filter_tahun);
        $builder->orderBy('presensi.tanggal', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function rekap_presensi_mahasiswa()
    {
        return $this->select('presensi.*, mahasiswa.nama, kelas.jam_masuk as jam_masuk_kampus, kelas.jam_pulang as jam_pulang_kampus, matkul.matkul as nama_matkul, kelas.hari as kelas_hari')
            ->join('mahasiswa', 'mahasiswa.id = presensi.id_mahasiswa')
            ->join('kelas', 'kelas.id_matkul = presensi.id_matkul')
            ->join('matkul', 'matkul.id = presensi.id_matkul')
            ->where('presensi.id_mahasiswa', session()->get('id_mahasiswa'))
            ->where('kelas.hari', Carbon::createFromFormat('Y-m-d',date('Y-m-d'))->locale('id')->translatedFormat('l'))
            ->orderBy('presensi.tanggal', 'DESC')
            ->findAll();
    }

    public function rekap_presensi_mahasiswa_filter($matkul)
    {
        return $this->select('presensi.*, mahasiswa.nama, kelas.jam_masuk as jam_masuk_kampus, kelas.jam_pulang as jam_pulang_kampus, matkul.matkul as nama_matkul, kelas.hari as kelas_hari')
            ->join('mahasiswa', 'mahasiswa.id = presensi.id_mahasiswa')
            ->join('kelas', 'kelas.id_matkul = presensi.id_matkul')
            ->join('matkul', 'matkul.id = presensi.id_matkul')
            ->where('presensi.id_mahasiswa', session()->get('id_mahasiswa'))
            ->where('matkul.id', $matkul)
            ->orderBy('presensi.tanggal', 'DESC')
            ->findAll();
    }
}
