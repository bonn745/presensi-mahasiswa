<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PegawaiModel;
use App\Models\UserPegawai;
use App\Models\LokasiPresensiModel;
use App\Models\JabatanModel;
use App\Models\UserModel;

class DataPegawai extends BaseController
{
    public function index()
    {
        $pegawaiModel = new PegawaiModel();
        $data = [
            'title' => 'Data Pegawai',
            'pegawai' => $pegawaiModel->findAll()
        ];
        return view('admin/data_pegawai/data_pegawai', $data);
    }

    function __construct()
    {
        helper(['url', 'form']);
    }

    public function detail($id)
    {
        $pegawaiModel = new PegawaiModel();
        $data = [
            'title' => 'Detail Data Pegawai',
            'pegawai' => $pegawaiModel->detailPegawai($id),
        ];
        return view('admin/data_pegawai/detail', $data);
    }

    public function create()
    {
        $lokasi_presensi = new LokasiPresensiMOdel();
        $jabatan_model = new JabatanModel();
        $data = [
            'title' => 'Tambah Pegawai',
            'lokasi_presensi' => $lokasi_presensi->findAll(),
            'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/data_pegawai/create', $data);
    }

    public function generateNIP()
    {
        $pegawaiModel = new PegawaiModel();
        $pegawaiTerakhir = $pegawaiModel->select('nip')->orderBy('id', 'DESC')->first();
        $nipTerakhir = $pegawaiTerakhir ? $pegawaiTerakhir['nip'] : 'PEG-000';
        // Mengambil angka dari indeks ke-4 sehingga "PEG-002" menjadi "002"
        $angkaNIP = (int) substr($nipTerakhir, 4);
        $angkaNIP++;
        return 'PEG-' . str_pad($angkaNIP, 3, '0', STR_PAD_LEFT);
    }


    public function store()
    {
        $rules = [

            'nama' => [
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
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Alamat Wajib Diisi"
                ],
            ],
            'no_handphone' => [
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => "No Handphone Wajib Diisi",
                    'numeric' => "No Handphone harus berupa angka",
                    'min_length' => "No Handphone minimal 10 digit",
                    'max_length' => "No Handphone maksimal 15 digit",
                ],
            ],

            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jabatan Wajib Diisi"
                ],
            ],
            'lokasi_presensi' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => "Lokasi Wajib Diisi",
                    'numeric' => "Lokasi Presensi harus berupa angka"
                ],
            ],
            'foto' => [
                'rules' => 'uploaded[foto]|max_size[foto,10240]|mime_in[foto,image/png,image/jpeg]',
                'errors' => [
                    'uploaded' => "Foto Wajib Diisi",
                    'max_size' => "Ukuran foto melebihi 10MB",
                    'mime_in' => "Jenis file yang diizinkan hanya PNG atau JPEG"
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
            'role' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Role Wajib Diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $lokasi_presensi = new LokasiPresensiMOdel();
            $jabatan_model = new JabatanModel();
            $data = [
                'title' => 'Tambah Pegawai',
                'lokasi_presensi' => $lokasi_presensi->findAll(),
                'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
                'validation' => \Config\Services::validation()
            ];
            return view('admin/data_pegawai/create', $data);
        } else {
            $pegawaiModel = new PegawaiModel();
            $nipBaru = $this->generateNIP();
            // dd($id_pegawai);

            $foto = $this->request->getFile('foto');

            if ($foto->getError() == 4) {
                $nama_foto = '';
            } else {
                $nama_foto = $foto->getRandomName();
                $foto->move('profile', $nama_foto);
            }

            $pegawaiModel = new PegawaiModel();
            $pegawaiModel->insert([
                'nip' => $nipBaru,
                'nama' => $this->request->getPost('nama'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'alamat' => $this->request->getPost('alamat'),
                'no_handphone' => $this->request->getPost('no_handphone'),
                'jabatan' => $this->request->getPost('jabatan'),
                'lokasi_presensi' => $this->request->getPost('lokasi_presensi'),
                'foto' => $nama_foto,
            ]);
            $id_pegawai = $pegawaiModel->insertID();
            $userModel = new UserModel();
            $userModel->insert([
                'id_pegawai' => $id_pegawai,
                'username' => $this->request->getPost('username'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'status' => 'aktif',
                'role' => $this->request->getPost('role')
            ]);
        }

        return redirect()->to('/admin/data_pegawai')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $lokasi_presensi = new LokasiPresensiMOdel();
        $jabatan_model = new JabatanModel();
        $pegawaiModel = new PegawaiModel();
        $data = [
            'title' => 'Edit Pegawai',
            'pegawai' => $pegawaiModel->editPegawai($id),
            'lokasi_presensi' => $lokasi_presensi->findAll(),
            'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/data_pegawai/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama' => [
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
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Alamat Wajib Diisi"
                ],
            ],
            'no_handphone' => [
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => "No Handphone Wajib Diisi",
                    'numeric' => "No Handphone harus berupa angka",
                    'min_length' => "No Handphone minimal 10 digit",
                    'max_length' => "No Handphone maksimal 15 digit",
                ],
            ],
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jabatan Wajib Diisi"
                ],
            ],
            'lokasi_presensi' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => "Lokasi Presensi Wajib Diisi",
                    'numeric' => "Lokasi Presensi harus berupa angka"
                ],
            ],
            'username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Username Wajib Diisi"
                ],
            ],
            'password' => [
                'rules' => 'permit_empty|min_length[6]',
                'errors' => [
                    'min_length' => "Password minimal 6 karakter"
                ],
            ],
            'role' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Role Wajib Diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $lokasi_presensi = new LokasiPresensiModel();
            $jabatan_model = new JabatanModel();
            $pegawaiModel = new PegawaiModel();

            $data = [
                'title' => 'Edit Pegawai',
                'pegawai' => $pegawaiModel->editPegawai($id),
                'lokasi_presensi' => $lokasi_presensi->findAll(),
                'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
                'validation' => \Config\Services::validation()
            ];

            return view('admin/data_pegawai/edit', $data);
        } else {
            $pegawaiModel = new PegawaiModel();
            $userModel = new UserModel();

            // Ambil data pegawai lama
            $pegawaiLama = $pegawaiModel->find($id);

            if (!$pegawaiLama) {
                session()->setFlashData('error', 'Data Pegawai tidak ditemukan');
                return redirect()->to(base_url('admin/data_pegawai'));
            }

            // Proses upload foto baru jika ada
            $foto = $this->request->getFile('foto');
            if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                $nama_foto = $foto->getRandomName();
                $foto->move('profile', $nama_foto);

                // Hapus foto lama jika ada
                if (!empty($pegawaiLama['foto']) && file_exists('profile/' . $pegawaiLama['foto'])) {
                    unlink('profile/' . $pegawaiLama['foto']);
                }
            } else {
                $nama_foto = $this->request->getPost('foto_lama'); // Pakai foto lama jika tidak ada upload baru
            }

            // Update data pegawai
            $pegawaiModel->update($id, [
                'nama' => $this->request->getPost('nama'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'alamat' => $this->request->getPost('alamat'),
                'no_handphone' => $this->request->getPost('no_handphone'),
                'jabatan' => $this->request->getPost('jabatan'),
                'lokasi_presensi' => $this->request->getPost('lokasi_presensi'),
                'foto' => $nama_foto,
            ]);

            // Cek apakah password diubah
            if ($this->request->getPost('password') == '') {
                $password = $this->request->getPost('password_lama');
            } else {
                $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }

            // Update data user
            $userModel
                ->where('id', $id)
                ->set([
                    'username' => $this->request->getPost('username'),
                    'password' => $password,
                    'status' => $this->request->getPost('status'),
                    'role' => $this->request->getPost('role'),
                ])
                ->update();

            session()->setFlashData('success', 'Data Pegawai Berhasil Diupdate');
            return redirect()->to(base_url('admin/data_pegawai'));
        }
    }

    public function delete($id)
    {
        $pegawaiModel = new PegawaiModel();
        $userModel = new UserModel(); // Tambahkan ini jika belum dideklarasikan
        $pegawai = $pegawaiModel->find($id); // Perbaikan pemanggilan variabel

        if ($pegawai) {
            $userModel->where('id_pegawai', $id)->delete(); // Hapus data user terkait
            $pegawaiModel->delete($id); // Hapus data pegawai

            return redirect()->to('/admin/data_pegawai')->with('success', 'Data Berhasil dihapus.');
        }

        return redirect()->to('/admin/data_pegawai')->with('error', 'Data Pegawai tidak ditemukan.');
    }
}
