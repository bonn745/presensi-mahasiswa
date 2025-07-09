<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LokasiPresensiModel;
use App\Models\MatkulModel;

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
        $matkul_model = new MatkulModel();
        $data = [
            'title' => 'Tambah Lokasi',
            'matkul' => $matkul_model->orderBy('matkul', 'ASC')->findAll(),
            'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
        ];
        return view('admin/lokasi_presensi/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama_ruangan' => [
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
        ];

        if (!$this->validate($rules)) {
            $matkul_model = new MatkulModel();
            $data = [
                'title' => 'Tambah Lokasi',
                'matkul' => $matkul_model->orderBy('matkul', 'ASC')->findAll(),
                'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
            ];
            return view('admin/lokasi_presensi/create', $data);
        }

        // Jika validasi berhasil, simpan data lokasi presensi
        $lokasipresensiModel = new LokasiPresensiModel();
        $lokasipresensiModel->save([
            'nama_ruangan' => $this->request->getPost('nama_ruangan'),
            'alamat_lokasi' => $this->request->getPost('alamat_lokasi'),
            'tipe_lokasi' => $this->request->getPost('tipe_lokasi'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'radius' => $this->request->getPost('radius'),
        ]);

        return redirect()->to('/admin/lokasi_presensi')->with('success', 'Lokasi berhasil ditambahkan.'); // Redirect setelah menyimpan
    }

    public function edit($id)
    {
        $lokasipresensiModel = new LokasiPresensiModel();
        $matkul_model = new MatkulModel();
        $data = [
            'title' => 'Edit Jabatan',
            'lokasi_presensi' => $lokasipresensiModel->find($id),
            'matkul' => $matkul_model->orderBy('matkul', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/lokasi_presensi/edit', $data);
    }

    public function update($id)
    {
        $lokasipresensiModel = new LokasiPresensiModel();

        $rules = [
            'nama_ruangan' => [
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
        ];

        if (!$this->validate($rules)) {
            $matkul_model = new MatkulModel();
            $data = [
                'title' => 'Edit Jabatan',
                'lokasi_presensi' => $lokasipresensiModel->find($id),
                'matkul' => $matkul_model->orderBy('matkul', 'ASC')->findAll(),
                'validation' => \Config\Services::validation()
            ];
            echo view('admin/lokasi_presensi/edit', $data);
        } else {
            $lokasipresensiModel = new LokasiPresensiModel();
            $lokasipresensiModel->update($id, [
                'nama_ruangan' => $this->request->getPost('nama_ruangan'),
                'alamat_lokasi' => $this->request->getPost('alamat_lokasi'),
                'tipe_lokasi' => $this->request->getPost('tipe_lokasi'),
                'latitude' => $this->request->getPost('latitude'),
                'longitude' => $this->request->getPost('longitude'),
                'radius' => $this->request->getPost('radius'),
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
