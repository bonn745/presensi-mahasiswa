<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KehadiranModel;
use CodeIgniter\HTTP\ResponseInterface;

class Kehadiran extends BaseController
{
    protected $kehadiranModel;

    public function __construct()
    {
        // Inisialisasi model sekali saja di konstruktor
        $this->kehadiranModel = new KehadiranModel();
    }

    public function index()
    {
        $data = [
            'title' => "Kehadiran",
            'kehadiran' => $this->kehadiranModel->getKehadiranWithPegawai()
        ];
        return view('admin/kehadiran', $data);
    }


    public function approved($id)
    {
        $data = $this->kehadiranModel->find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $this->kehadiranModel->update($id, [
            'status_pengajuan' => 'Approved'
        ]);

        return redirect()->back()->with('updated', 'Pengajuan berhasil disetujui.');
    }

    public function rejected($id)
    {
        $kehadiran = $this->kehadiranModel->find($id);

        if (!$kehadiran) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $this->kehadiranModel->update($id, [
            'status_pengajuan' => 'Rejected'
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function delete($id)
    {
        $kehadiran = $this->kehadiranModel->find($id);

        if (!$kehadiran) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Delete file if exists
        if (!empty($kehadiran['file'])) {
            $file_path = FCPATH . 'file_kehadiran/' . $kehadiran['file'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $this->kehadiranModel->delete($id);
        return redirect()->back()->with('success', 'Data kehadiran berhasil dihapus.');
    }
}
