<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\DosenModel;
use App\Models\UserDosen;
use App\Models\MatkulModel;
use App\Models\KelasModel;
use App\Models\UserModel;

class DataDosen extends BaseController
{
    public function index()
    {
        $dosenModel = new DosenModel();
        $data = [
            'title' => 'Data Dosen',
            'dosen' => $dosenModel->findAll()
        ];
        return view('admin/data_dosen/data_dosen', $data);
    }

    function __construct()
    {
        helper(['url', 'form']);
    }

    public function detail($id)
    {
        $dosenModel = new DosenModel();
        $data = [
            'title' => 'Detail Data Dosen',
            'dosen' => $dosenModel->detailDosen($id),
            'jadwal_ngajar' => $dosenModel->getJadwalNgajarByDosenId($id)
        ];
        return view('admin/data_dosen/detail', $data);
    }

    public function create()
    {
        $matkul_model = new MatkulModel();
        $kelas_model = new KelasModel();
        $data = [
            'title' => 'Tambah Dosen',
            'matkul' => $matkul_model->orderBy('matkul', 'ASC')->findAll(),
            'kelas' => $kelas_model->orderBy('id_matkul', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/data_dosen/create', $data);
    }

    public function generateNIDN()
    {
        $dosenModel = new DosenModel();
        $dosenTerakhir = $dosenModel->select('nidn')->orderBy('id', 'DESC')->first();
        $nimTerakhir = $dosenTerakhir ? $dosenTerakhir['nidn'] : 'DSN-000';
        // Mengambil angka dari indeks ke-4 sehingga "PEG-002" menjadi "002"
        $angkaNIDN = (int) substr($nimTerakhir, 4);
        $angkaNIDN++;
        return 'DSN-' . str_pad($angkaNIDN, 3, '0', STR_PAD_LEFT);
    }


    public function store()
    {
        $rules = [

            'nama_dosen' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Wajib Diisi"
                ],
            ],
            'jenis_kelamin' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jenis Kelamin Wajib Diisi"
                ],
            ],
            'no_hp' => [
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => "No Handphone Wajib Diisi",
                    'numeric' => "No Handphone harus berupa angka",
                    'min_length' => "No Handphone minimal 10 digit",
                    'max_length' => "No Handphone maksimal 15 digit",
                ],
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Alamat Wajib Diisi"
                ],
            ],
            'username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Username Wajib Diisi"
                ],
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Password Wajib Diisi"
                ],
            ],
            'konfirmasi_password' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => "Konfirmasi Password Wajib Diisi",
                    'matches' => "Konfirmasi password tidak cocok"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $matkul_model = new MatkulModel();
            $kelas_model = new KelasModel();
            $data = [
                'title' => 'Tambah Dosen',
                'matkul' => $matkul_model->orderBy('matkul', 'ASC')->findAll(),
                'kelas' => $kelas_model->orderBy('id_matkul', 'ASC')->findAll(),
                'validation' => \Config\Services::validation()
            ];
            return view('admin/data_dosen/create', $data);
        } else {
            $dosenModel = new DosenModel();
            $nidnBaru = $this->generateNIDN();

            $dosenModel = new DosenModel();
            $dosenModel->insert([
                'nidn' => $nidnBaru,
                'nama_dosen' => $this->request->getPost('nama_dosen'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'no_hp' => $this->request->getPost('no_hp'),
                'alamat' => $this->request->getPost('alamat'),

            ]);
            $id_dosen = $dosenModel->insertID();
            $userModel = new UserModel();
            $userModel->insert([
                'id_dosen' => $id_dosen,
                'username' => $this->request->getPost('username'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'status' => 'aktif',
                'role' => 'dosen'
            ]);
        }

        return redirect()->to('/admin/data_dosen')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $matkul_model = new MatkulModel();
        $dosenModel = new DosenModel();
        $data = [
            'title' => 'Edit Dosen',
            'dosen' => $dosenModel->editdosen($id),
            'matkul' => $matkul_model->orderBy('matkul', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/data_dosen/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama_dosen' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Wajib Diisi"
                ],
            ],
            'jenis_kelamin' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jenis Kelamin Wajib Diisi"
                ],
            ],
            'no_hp' => [
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => "No Handphone Wajib Diisi",
                    'numeric' => "No Handphone harus berupa angka",
                    'min_length' => "No Handphone minimal 10 digit",
                    'max_length' => "No Handphone maksimal 15 digit",
                ],
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Alamat Wajib Diisi"
                ],
            ],
            'username' => [
                'rules' => 'required|is_unique[users.username,id_dosen,' . $id . ']',
                'errors' => [
                    'required' => "Username Wajib Diisi",
                    'is_unique' => "Username sudah digunakan"
                ],
            ],
            'password' => [
                'rules' => 'permit_empty|min_length[6]',
                'errors' => [
                    'min_length' => "Password minimal 6 karakter"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $matkul_model = new MatkulModel();
            $dosenModel = new DosenModel();

            $data = [
                'title' => 'Edit Dosen',
                'dosen' => $dosenModel->editdosen($id),
                'matkul' => $matkul_model->orderBy('matkul', 'ASC')->findAll(),
                'validation' => \Config\Services::validation()
            ];

            return view('admin/data_dosen/edit', $data);
        } else {
            $dosenModel = new DosenModel();
            $userModel = new UserModel();

            // Ambil data dosen lama
            $dosenLama = $dosenModel->find($id);

            if (!$dosenLama) {
                session()->setFlashData('error', 'Data dosen tidak ditemukan');
                return redirect()->to(base_url('admin/data_dosen'));
            }

            // Update data mahasiswa
            $dosenModel->update($id, [

                'nama_dosen' => $this->request->getPost('nama_dosen'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'no_hp' => $this->request->getPost('no_hp'),
                'alamat' => $this->request->getPost('alamat'),
            ]);

            // Cek apakah password diubah
            if ($this->request->getPost('password') == '') {
                $password = $this->request->getPost('password_lama');
            } else {
                $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }

            // Update data user
            $userModel
                ->where('id_dosen', $id)
                ->set([
                    'username' => $this->request->getPost('username'),
                    'password' => $password,
                    'status' => $this->request->getPost('status'),
                    'role' => 'dosen'
                ])
                ->update();

            session()->setFlashData('success', 'Data dosen Berhasil Diupdate');
            return redirect()->to(base_url('admin/data_dosen'));
        }
    }

    public function delete($id)
    {
        $dosenModel = new DosenModel();
        $userModel = new UserModel();
        $mahasiswa = $dosenModel->find($id);

        if ($mahasiswa) {
            $userModel->where('id_dosen', $id)->delete();
            $dosenModel->delete($id);

            return redirect()->to('/admin/data_dosen')->with('success', 'Data Berhasil dihapus.');
        }

        return redirect()->to('/admin/data_dosen')->with('error', 'Data mahasiswa tidak ditemukan.');
    }

    public function jadwal($id)
    {
        $dosenModel = new \App\Models\DosenModel();

        // Ambil data jadwal dosen berdasarkan ID
        $jadwal = $dosenModel->getJadwalNgajarByDosenId($id);

        if (!$jadwal) {
            // Tampilkan 404 jika data tidak ditemukan
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Jadwal dosen dengan ID $id tidak ditemukan.");
        }

        $data = [
            'title'  => 'Jadwal Ngajar Dosen',
            'jadwal' => $jadwal,
        ];

        return view('admin/data_dosen/jadwal', $data);
    }

    public function get_jadwal($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([]);
        }

        $dosenModel = new \App\Models\DosenModel();
        $jadwal = $dosenModel->getJadwalNgajarByDosenId($id);

        if (!$jadwal) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON($jadwal);
    }
}
