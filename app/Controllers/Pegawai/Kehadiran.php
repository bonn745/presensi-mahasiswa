<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;
use App\Models\KehadiranModel;
use CodeIgniter\HTTP\ResponseInterface;

class Kehadiran extends BaseController
{
    function __construct()
    {
        helper(['url', 'form']);
    }

    public function index()
    {
        $kehadiranModel = new KehadiranModel();
        $id_pegawai = session()->get('id_pegawai');
        $data = [
            'title' => "Kehadiran",
            'kehadiran' => $kehadiranModel->where('id_pegawai', $id_pegawai)->findAll()
        ];
        return view('pegawai/kehadiran', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Ajukan Permohonan',
            'validation' => \Config\Services::validation()
        ];
        return view('pegawai/create_kehadiran', $data);
    }

    public function store()
    {
        $rules = [

            'keterangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Keterangan Wajib Diisi"
                ],
            ],
            'tanggal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal Wajib Diisi"
                ],
            ],
            'deskripsi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Deskripsi Wajib Diisi"
                ],
            ],
            'file' => [
                'rules' => 'uploaded[file]|max_size[file,10240]|mime_in[file,image/png,image/jpeg,application/pdf]',
                'errors' => [
                    'uploaded' => "File Wajib Diisi",
                    'max_size' => "Ukuran file melebihi 10MB",
                    'mime_in' => "Jenis file yang diizinkan hanya PNG, JPEG atau PDF"
                ],
            ],
        ];


        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Ajukan Permohonan',
                'validation' => \Config\Services::validation()
            ];
            return view('pegawai/create_kehadiran', $data);
        } else {
            $kehadiranModel = new KehadiranModel();

            $file = $this->request->getFile('file');

            if ($file->getError() == 4) {
                $nama_file = '';
            } else {
                $nama_file = $file->getRandomName();
                $file->move('file_kehadiran', $nama_file);
            }


            $kehadiranModel->insert([
                'keterangan' => $this->request->getPost('keterangan'),
                'tanggal' => $this->request->getPost('tanggal'),
                'id_pegawai' => $this->request->getPost('id_pegawai'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'status_pengajuan' => 'Pending',
                'file' => $nama_file,
            ]);
        }

        return redirect()->to('/pegawai/kehadiran')->with('success', 'Permohonan berhasil diajukan.');
    }

    public function edit($id)
    {
        $kehadiranModel = new KehadiranModel();
        $data = [
            'title' => 'Edit Permohonan',
            'kehadiran' => $kehadiranModel->find($id),
            'validation' => \Config\Services::validation()
        ];

        return view('pegawai/edit_kehadiran', $data);
    }

    public function update($id)
    {
        $kehadiranModel = new KehadiranModel();
        $rules = [
            'keterangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Keterangan Wajib Diisi"
                ],
            ],
            'tanggal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal Wajib Diisi"
                ],
            ],
            'deskripsi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Deskripsi Wajib Diisi"
                ],
            ],
            'file' => [
                'rules' => 'max_size[file,10240]|mime_in[file,image/png,image/jpeg,application/pdf]',
                'errors' => [
                    'max_size' => "Ukuran file melebihi 10MB",
                    'mime_in' => "Jenis file yang diizinkan hanya PNG, JPEG atau PDF"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Edit Permohonan',
                'kehadiran' => $kehadiranModel->find($id),
                'validation' => \Config\Services::validation()
            ];
            return view('pegawai/edit_kehadiran', $data);
        } else {
            // Ambil data kehadiran lama
            $kehadiranLama = $kehadiranModel->find($id);

            if (!$kehadiranLama) {
                session()->setFlashData('error', 'Data Kehadiran tidak ditemukan');
                return redirect()->to(base_url('pegawai/kehadiran'));
            }

            // Proses upload file baru jika ada
            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $nama_file = $file->getRandomName();
                $file->move('file_kehadiran', $nama_file);

                // Hapus file lama jika ada
                if (!empty($kehadiranLama['file']) && file_exists('file_kehadiran/' . $kehadiranLama['file'])) {
                    unlink('file_kehadiran/' . $kehadiranLama['file']);
                }
            } else {
                // Gunakan file lama jika tidak ada upload baru
                $nama_file = $kehadiranLama['file'];
            }

            // Update data kehadiran
            $kehadiranModel->update($id, [
                'keterangan' => $this->request->getPost('keterangan'),
                'tanggal' => $this->request->getPost('tanggal'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'file' => $nama_file,
                'status_pengajuan' => 'Pending', // Status bisa disesuaikan sesuai kebutuhan
            ]);

            session()->setFlashdata('updated', 'Pengajuan berhasil diperbarui.');
            return redirect()->to(base_url('pegawai/kehadiran'));
        }
    }

    public function delete($id)
    {
        $kehadiranModel = new KehadiranModel();

        // Cari data berdasarkan ID
        $kehadiran = $kehadiranModel->find($id);

        if (!$kehadiran) {
            session()->setFlashdata('error', 'Data Kehadiran tidak ditemukan');
            return redirect()->to(base_url('pegawai/kehadiran'));
        }

        // Hapus file jika ada
        if (!empty($kehadiran['file']) && file_exists('file_kehadiran/' . $kehadiran['file'])) {
            unlink('file_kehadiran/' . $kehadiran['file']);
        }

        // Hapus data dari database
        $kehadiranModel->delete($id);

        session()->setFlashdata('deleted', 'Pengajuan berhasil dihapus.');
        return redirect()->to(base_url('pegawai/kehadiran'));
    }
}
