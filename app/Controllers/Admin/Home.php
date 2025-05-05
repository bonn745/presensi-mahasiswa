<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;
use App\Models\PresensiModel;
use App\Models\KehadiranModel;
use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');

        $pegawai_model = new PegawaiModel();
        $presensi_model = new PresensiModel();
        $kehadiran_model = new KehadiranModel();

        // Ambil tanggal dari input (GET) atau gunakan hari ini sebagai default
        $tanggal = $this->request->getGet('tanggal');
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        // Menghitung total pegawai, total hadir, dan kehadiran hanya untuk tanggal tertentu
        $data = [
            'title' => 'Home',
            'tanggal_filter' => $tanggal,
            'total_pegawai' => $pegawai_model->countAllResults(),
            'total_hadir' => $presensi_model->where('tanggal_masuk', $tanggal)->countAllResults(), // Menghitung hanya untuk tanggal tertentu
            'kehadiran' => $kehadiran_model->where('tanggal', $tanggal)->countAllResults(), // Menghitung kehadiran sesuai tanggal
        ];

        return view('admin/home', $data);
    }
}
