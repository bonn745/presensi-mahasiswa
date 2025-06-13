<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\ketidakhadiranModel;
use CodeIgniter\HTTP\ResponseInterface;

class Ketidakhadiran extends BaseController
{
    protected $validation;

    function __construct()
    {
        helper(['url', 'form']);
        $this->validation = \Config\Services::validation();
    }

    protected function validateDates($tanggal_selesai, $data)
    {
        $tanggal_mulai = $data['tanggal_mulai'];
        return strtotime($tanggal_selesai) >= strtotime($tanggal_mulai);
    }

    public function index()
    {
        $ketidakhadiranModel = new KetidakhadiranModel();
        $id_mahasiswa = session()->get('id_mahasiswa');
        $data = [
            'title' => "Permohonan Izin",
            'ketidakhadiran' => $ketidakhadiranModel->where('id_mahasiswa', $id_mahasiswa)->findAll()
        ];
        return view('mahasiswa/ketidakhadiran', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Ajukan Permohonan',
            'validation' => $this->validation
        ];
        return view('mahasiswa/create_ketidakhadiran', $data);
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
            'tanggal_mulai' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal Mulai Wajib Diisi"
                ],
            ],
            'tanggal_selesai' => [
                'rules' => 'required|check_date[tanggal_mulai]',
                'errors' => [
                    'required' => "Tanggal Selesai Wajib Diisi",
                    'check_date' => "Tanggal Selesai harus sama dengan atau setelah Tanggal Mulai"
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

        // Tambahkan custom validation rule
        $this->validation->setRule('tanggal_selesai', 'Tanggal Selesai', 'required|check_date[tanggal_mulai]', [
            'check_date' => 'Tanggal Selesai harus sama dengan atau setelah Tanggal Mulai'
        ]);

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Ajukan Permohonan',
                'validation' => $this->validation
            ];
            return view('mahasiswa/create_ketidakhadiran', $data);
        }

        $ketidakhadiranModel = new KetidakhadiranModel();

        $file = $this->request->getFile('file');
        if ($file->getError() == 4) {
            $nama_file = '';
        } else {
            $nama_file = $file->getRandomName();
            $file->move('file_ketidakhadiran', $nama_file);
        }

        $ketidakhadiranModel->insert([
            'keterangan' => $this->request->getPost('keterangan'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'id_mahasiswa' => $this->request->getPost('id_mahasiswa'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'status_pengajuan' => 'Pending',
            'file' => $nama_file,
        ]);

        return redirect()->to('/mahasiswa/ketidakhadiran')->with('success', 'Permohonan berhasil diajukan.');
    }

    public function edit($id)
    {
        $ketidakhadiranModel = new KetidakhadiranModel();
        $data = [
            'title' => 'Edit Permohonan',
            'ketidakhadiran' => $ketidakhadiranModel->find($id),
            'validation' => $this->validation
        ];

        return view('mahasiswa/edit_ketidakhadiran', $data);
    }

    public function update($id)
    {
        $ketidakhadiranModel = new KetidakhadiranModel();
        $rules = [
            'keterangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Keterangan Wajib Diisi"
                ],
            ],
            'tanggal_mulai' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal Mulai Wajib Diisi"
                ],
            ],
            'tanggal_selesai' => [
                'rules' => 'required|check_date[tanggal_mulai]',
                'errors' => [
                    'required' => "Tanggal Selesai Wajib Diisi",
                    'check_date' => "Tanggal Selesai harus sama dengan atau setelah Tanggal Mulai"
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

        // Tambahkan custom validation rule
        $this->validation->setRule('tanggal_selesai', 'Tanggal Selesai', 'required|check_date[tanggal_mulai]', [
            'check_date' => 'Tanggal Selesai harus sama dengan atau setelah Tanggal Mulai'
        ]);

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Edit Permohonan',
                'ketidakhadiran' => $ketidakhadiranModel->find($id),
                'validation' => $this->validation
            ];
            return view('mahasiswa/edit_ketidakhadiran', $data);
        }

        // Ambil data ketidakhadiran lama
        $ketidakhadiranLama = $ketidakhadiranModel->find($id);

        if (!$ketidakhadiranLama) {
            session()->setFlashData('error', 'Data ketidakhadiran tidak ditemukan');
            return redirect()->to(base_url('mahasiswa/ketidakhadiran'));
        }

        // Proses upload file baru jika ada
        $file = $this->request->getFile('file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $nama_file = $file->getRandomName();
            $file->move('file_ketidakhadiran', $nama_file);

            // Hapus file lama jika ada
            if (!empty($ketidakhadiranLama['file']) && file_exists('file_ketidakhadiran/' . $ketidakhadiranLama['file'])) {
                unlink('file_ketidakhadiran/' . $ketidakhadiranLama['file']);
            }
        } else {
            // Gunakan file lama jika tidak ada upload baru
            $nama_file = $ketidakhadiranLama['file'];
        }

        // Update data ketidakhadiran
        $ketidakhadiranModel->update($id, [
            'keterangan' => $this->request->getPost('keterangan'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'file' => $nama_file,
            'status_pengajuan' => 'Pending',
        ]);

        session()->setFlashdata('updated', 'Pengajuan berhasil diperbarui.');
        return redirect()->to(base_url('mahasiswa/ketidakhadiran'));
    }
}
