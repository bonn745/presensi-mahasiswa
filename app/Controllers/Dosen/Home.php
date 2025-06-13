<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\PresensiModel;
use App\Models\KehadiranModel;
use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');

        $mahasiswa_model = new MahasiswaModel();
        $presensi_model = new PresensiModel();


        // Ambil tanggal dari input (GET) atau gunakan hari ini sebagai default
        $tanggal = $this->request->getGet('tanggal');
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        // Mengambil daftar mahasiswa yang hadir hari ini (unik berdasarkan id_mahasiswa)
        $db = \Config\Database::connect();
        $builder = $db->table('presensi');
        $mahasiswa_hadir = $builder->select('id_mahasiswa')
            ->where('tanggal_masuk', $tanggal)
            ->distinct()
            ->get()
            ->getResultArray();

        // Debug info
        log_message('debug', 'Jumlah mahasiswa hadir: ' . count($mahasiswa_hadir));

        // Hitung total mahasiswa
        $total_mahasiswa = $mahasiswa_model->countAllResults();

        // Hitung mahasiswa hadir (berdasarkan id unik)
        $total_hadir = count($mahasiswa_hadir);

        // Debug info
        log_message('debug', 'Total mahasiswa: ' . $total_mahasiswa);
        log_message('debug', 'Total hadir: ' . $total_hadir);

        $data = [
            'title' => 'Dashboard',
            'tanggal_filter' => $tanggal,
            'total_mahasiswa' => $total_mahasiswa,
            'total_hadir' => $total_hadir,

        ];

        return view('dosen/home', $data);
    }
}
