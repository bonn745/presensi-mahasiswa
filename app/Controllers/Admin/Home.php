<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\PresensiModel;
use App\Models\DosenModel;
use App\Models\KelasModel;
use App\Models\KetidakhadiranModel;
use App\Models\MatkulMahasiswa;
use Carbon\Carbon;
use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');

        $mahasiswa_model = new MahasiswaModel();
        $presensi_model = new PresensiModel();
        $dosen_model = new DosenModel();

        // Ambil tanggal dari input (GET) atau gunakan hari ini sebagai default
        $tanggal = $this->request->getGet('tanggal');
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        $jamSekarang = date('H:i:s');

        $namaHari = Carbon::createFromFormat('Y-m-d', $tanggal)->locale('id')->translatedFormat('l');

        $kelasModel = new KelasModel();

        $matkulMahasiswaModel  = new MatkulMahasiswa();

        $ketidakHadiranModel = new KetidakhadiranModel();

        $idKelasHariIni = array_column($kelasModel->select('id_matkul')->where('hari', $namaHari)->where('jam_masuk <=', $jamSekarang)->findAll(), 'id_matkul');

        $idMahasiswa = [];

        if (count($idKelasHariIni) > 0) {
            $idMahasiswa = array_column($matkulMahasiswaModel->select('mhs_id')->whereIn('matkul_id', $idKelasHariIni)->findAll(), 'mhs_id');
        }

        $idMahasiswaIzin = array_column($ketidakHadiranModel->select('id_mahasiswa')->where('tanggal', $tanggal)->where('status_pengajuan', 'Accept')->distinct()->findAll(), 'id_mahasiswa');

        $idMahasiswaAbsen = [];

        if (count($idMahasiswa) > 0 && count($idMahasiswaIzin) > 0) {
            $idMahasiswaAbsen = array_filter($idMahasiswa, function ($item) use ($idMahasiswaIzin) {
                return !in_array($item, $idMahasiswaIzin);
            });
        }

        $dataMahasiswaIzin = [];

        if (count($idMahasiswaIzin) > 0) {
            $dataMahasiswaIzin = $ketidakHadiranModel->select('mahasiswa.npm, mahasiswa.nama, matkul.matkul as nama_matkul, ketidakhadiran.keterangan')
                ->where('tanggal', $tanggal)
                ->where('status_pengajuan', 'Accept')
                ->whereIn('id_mahasiswa', $idMahasiswaIzin)
                ->join('mahasiswa', 'mahasiswa.id = ketidakhadiran.id_mahasiswa')
                ->join('matkul', 'matkul.id = ketidakhadiran.id_matkul')
                ->findAll();
        }

        // Mengambil daftar mahasiswa yang hadir hari ini (unik berdasarkan id_mahasiswa)
        $db = \Config\Database::connect();
        $builder = $db->table('presensi');
        $mahasiswa_hadir = $builder->select('id_mahasiswa')
            ->where('tanggal', $tanggal)
            ->distinct()
            ->get()
            ->getResultArray();

        if (count($idMahasiswa) > 0 && count($mahasiswa_hadir) > 0) {
            $idMahasiswaAbsen = array_filter($idMahasiswaAbsen, function ($item) use ($mahasiswa_hadir) {
                return !in_array($item, array_column($mahasiswa_hadir, 'id_mahasiswa'));
            });
        }

        $dataMahasiswaAbsen = [];

        foreach (array_values($idMahasiswaAbsen) as $ma) {
            $kelas = $kelasModel->select('mahasiswa.npm, mahasiswa.nama, matkul.matkul as nama_matkul')
                ->where('hari', $namaHari)
                ->where('matkul_mahasiswa.mhs_id', $ma)
                ->join('matkul', 'matkul.id = kelas.id_matkul')
                ->join('matkul_mahasiswa', 'matkul_mahasiswa.matkul_id = kelas.id_matkul')
                ->join('mahasiswa', 'mahasiswa.id = matkul_mahasiswa.mhs_id')
                ->findAll();

            $dataMahasiswaAbsen[] = $kelas;
        }


        // Debug info
        log_message('debug', 'Jumlah mahasiswa hadir: ' . count($mahasiswa_hadir));

        // Hitung total mahasiswa
        // $total_mahasiswa = $mahasiswa_model->countAllResults();
        $total_mahasiswa = count($idMahasiswa);

        // Hitung mahasiswa hadir (berdasarkan id unik)
        $total_hadir = count($mahasiswa_hadir);

        $total_izin = count($idMahasiswaIzin);

        $total_absen = count(array_values($idMahasiswaAbsen));

        // Debug info
        log_message('debug', 'Total mahasiswa: ' . $total_mahasiswa);
        log_message('debug', 'Total hadir: ' . $total_hadir);

        $data = [
            'title' => 'Dashboard',
            'tanggal_filter' => $tanggal,
            'total_mahasiswa' => $total_mahasiswa,
            'total_hadir' => $total_hadir,
            'total_izin' => $total_izin,
            'total_absen' => $total_absen,
            'total_dosen' => $dosen_model->countAllResults(),
            'data_mahasiswa_izin' => $dataMahasiswaIzin,
            'data_mahasiswa_absen' => $dataMahasiswaAbsen,
        ];

        return view('admin/home', $data);
    }
}
