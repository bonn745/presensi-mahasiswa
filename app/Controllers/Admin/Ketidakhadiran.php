<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KetidakhadiranModel;
use CodeIgniter\HTTP\ResponseInterface;

class Ketidakhadiran extends BaseController
{
    protected $ketidakhadiranModel;

    public function __construct()
    {
        // Inisialisasi model sekali saja di konstruktor
        $this->ketidakhadiranModel = new KetidakhadiranModel();
    }

    public function index()
    {
        $data = [
            'title' => "Ketidakhadiran",
            'ketidakhadiran' => $this->ketidakhadiranModel->getKetidakhadiranWithMahasiswa()
        ];
        return view('admin/ketidakhadiran', $data);
    }


    public function approved($id)
    {
        $data = $this->ketidakhadiranModel->find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $this->ketidakhadiranModel->update($id, [
            'status_pengajuan' => 'Approved'
        ]);

        return redirect()->back()->with('updated', 'Pengajuan berhasil disetujui.');
    }

    public function rejected($id)
    {
        $ketidakhadiran = $this->ketidakhadiranModel->find($id);

        if (!$ketidakhadiran) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $this->ketidakhadiranModel->update($id, [
            'status_pengajuan' => 'Rejected'
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function delete($id)
    {
        $ketidakhadiran = $this->ketidakhadiranModel->find($id);

        if (!$ketidakhadiran) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Delete file if exists
        if (!empty($ketidakhadiran['file'])) {
            $file_path = FCPATH . 'file_ketidakhadiran/' . $ketidakhadiran['file'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $this->ketidakhadiranModel->delete($id);
        return redirect()->back()->with('success', 'Data Ketidakhadiran berhasil dihapus.');
    }
}
