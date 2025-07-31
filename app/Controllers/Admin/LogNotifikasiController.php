<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LogNotifikasiPresensiModel;
use CodeIgniter\HTTP\ResponseInterface;

class LogNotifikasiController extends BaseController
{
    public function index()
    {
        $logNotifikasimodel = new LogNotifikasiPresensiModel();
        $dataLog = $logNotifikasimodel->findAll();
        $data = [
            'title' => 'Log Notifikasi Rekap Presensi Mahasiswa',
            'data_log' => $dataLog
        ];

        return view('admin/notifikasi/index', $data);
    }
}
