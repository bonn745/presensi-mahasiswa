<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LokasiPresensiModel;

class LokasiPresensi extends BaseController
{

    public function index()
    {
        $lokasipresensiModel = new LokasiPresensiModel(); // Gunakan huruf kapital untuk nama kelas
        $data = [
            'title' => 'Data Lokasi Presensi',
            'lokasi_presensi' => $lokasipresensiModel->findAll() // Mengambil semua data jabatan
        ];
        return view('admin/lokasi_presensi/lokasi_presensi', $data); // Pastikan view ini ada
    }

    function __construct()
    {
        helper(['url', 'form']);
    }

    public function detail($id)
    {
        $lokasipresensiModel = new LokasiPresensiModel();
        $data = [
            'title' => 'Detail Lokasi Presensi',
            'lokasi_presensi' => $lokasipresensiModel->find($id),
        ];
        return view('admin/lokasi_presensi/detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Lokasi',
            'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
        ];
        return view('admin/lokasi_presensi/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Lokasi Wajib Diisi"
                ],
            ],
            'alamat_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Alamat Lokasi Wajib Diisi"
                ],
            ],
            'tipe_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tipe Lokasi Wajib Diisi"
                ],
            ],
            'jadwal_kerja' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jadwal Kerja Wajib Diisi"
                ],
            ],
            'latitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Latitude Wajib Diisi"
                ],
            ],
            'longitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Longitude Wajib Diisi"
                ],
            ],
            'radius' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => "Radius Wajib Diisi",
                    'numeric' => "Radius harus berupa angka"
                ],
            ],
            'jam_masuk' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jam Masuk Wajib Diisi"
                ],
            ],
            'jam_pulang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jam Pulang Wajib Diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Tambah Lokasi',
                'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
            ];
            return view('admin/lokasi_presensi/create', $data);
        }

        // Jika validasi berhasil, simpan data lokasi presensi
        $lokasipresensiModel = new LokasiPresensiModel();
        $lokasipresensiModel->save([
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'alamat_lokasi' => $this->request->getPost('alamat_lokasi'),
            'tipe_lokasi' => $this->request->getPost('tipe_lokasi'),
            'jadwal_kerja' => $this->request->getPost('jadwal_kerja'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'radius' => $this->request->getPost('radius'),
            'zona_waktu' => $this->request->getPost('zona_waktu'),
            'jam_masuk' => $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang')
        ]);

        return redirect()->to('/admin/lokasi_presensi')->with('success', 'Lokasi berhasil ditambahkan.'); // Redirect setelah menyimpan
    }

    public function edit($id)
    {
        $lokasipresensiModel = new LokasiPresensiModel();
        $data = [
            'title' => 'Edit Jabatan',
            'lokasi_presensi' => $lokasipresensiModel->find($id),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/lokasi_presensi/edit', $data);
    }

    public function update($id)
    {
        $lokasipresensiModel = new LokasiPresensiModel();

        $rules = [
            'nama_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Lokasi Wajib Diisi"
                ],
            ],
            'alamat_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Alamat Lokasi Wajib Diisi"
                ],
            ],
            'tipe_lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tipe Lokasi Wajib Diisi"
                ],
            ],
            'jadwal_kerja' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jadwal Kerja Wajib Diisi"
                ],
            ],
            'latitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Latitude Wajib Diisi"
                ],
            ],
            'longitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Longitude Wajib Diisi"
                ],
            ],
            'radius' => [
                'rules' => 'required|numeric', // Menambahkan validasi numeric untuk radius
                'errors' => [
                    'required' => "Radius Wajib Diisi",
                    'numeric' => "Radius harus berupa angka"
                ],
            ],
            'jam_masuk' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jam Masuk Wajib Diisi"
                ],
            ],
            'jam_pulang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jam Pulang Wajib Diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Edit Jabatan',
                'lokasi_presensi' => $lokasipresensiModel->find($id),
                'validation' => \Config\Services::validation()
            ];
            echo view('admin/lokasi_presensi/edit', $data);
        } else {
            $lokasipresensiModel = new LokasiPresensiModel();
            $lokasipresensiModel->update($id, [
                'nama_lokasi' => $this->request->getPost('nama_lokasi'),
                'alamat_lokasi' => $this->request->getPost('alamat_lokasi'),
                'tipe_lokasi' => $this->request->getPost('tipe_lokasi'),
                'jadwal_kerja' => $this->request->getPost('jadwal_kerja'),
                'latitude' => $this->request->getPost('latitude'),
                'longitude' => $this->request->getPost('longitude'),
                'radius' => $this->request->getPost('radius'),
                'zona_waktu' => $this->request->getPost('zona_waktu'),
                'jam_masuk' => $this->request->getPost('jam_masuk'),
                'jam_pulang' => $this->request->getPost('jam_pulang')
            ]);

            session()->setFlashData('success', 'Data Lokasi Berhasil di Update');

            return redirect()->to(base_url('admin/lokasi_presensi'));
        }
    }

    public function delete($id)
    {
        $lokasipresensiModel = new LokasiPresensiModel();

        // Cek apakah jabatan dengan ID yang diberikan ada
        $lokasi_presensi = $lokasipresensiModel->find($id);
        if (!$lokasi_presensi) {
            return redirect()->to('/admin/lokasi_presensi')->with('error', 'Lokasi tidak ditemukan.');
        }

        // Hapus jabatan
        $lokasipresensiModel->delete($id);

        // Redirect dengan pesan sukses khusus untuk penghapusan
        return redirect()->to('/admin/lokasi_presensi')->with('success', 'Data Berhasil dihapus.');
    }
}
