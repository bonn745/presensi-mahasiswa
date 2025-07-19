<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Controllers\ImportController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MahasiswaModel;
use App\Models\UserMahasiswa;
use App\Models\LokasiPresensiModel;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataMahasiswa extends BaseController
{
    public function index()
    {
        $mahasiswaModel = new MahasiswaModel();
        $data = [
            'title' => 'Data mahasiswa',
            'mahasiswa' => $mahasiswaModel->findAll()
        ];
        return view('admin/data_mahasiswa/data_mahasiswa', $data);
    }

    function __construct()
    {
        helper(['url', 'form']);
    }

    public function detail($id)
    {
        //cobagithub
        $mahasiswaModel = new MahasiswaModel();
        $data = [
            'title' => 'Detail Data mahasiswa',
            'mahasiswa' => $mahasiswaModel->detailMahasiswa($id),
        ];
        return view('admin/data_mahasiswa/detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Mahasiswa',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/data_mahasiswa/create', $data);
    }

    public function generateNIM()
    {
        $mahasiswaModel = new MahasiswaModel();
        $mahasiswaTerakhir = $mahasiswaModel->select('nim')->orderBy('id', 'DESC')->first();
        $nimTerakhir = $mahasiswaTerakhir ? $mahasiswaTerakhir['nim'] : 'MHS-000';
        // Mengambil angka dari indeks ke-4 sehingga "PEG-002" menjadi "002"
        $angkaNIM = (int) substr($nimTerakhir, 4);
        $angkaNIM++;
        return 'MHS-' . str_pad($angkaNIM, 3, '0', STR_PAD_LEFT);
    }

    public function store()
    {
        $rules = [
            'nim' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "NIM Wajib Diisi"
                ],
            ],
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
            'semester' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Semester Wajib Diisi"
                ],
            ],
            'jurusan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jurusan Wajib Diisi"
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
            'nama_ortu' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Orang Tua Wajib Diisi"
                ],
            ],
            'jenis_kelamin_ortu' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jenis Kelamin Orang Tua Wajib Diisi"
                ],
            ],
            'no_whatsapp' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "No WhatsApp Orang Tua Wajib Diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Tambah Mahasiswa',
                'validation' => \Config\Services::validation()
            ];
            return view('admin/data_mahasiswa/create', $data);
        }

        $mahasiswaModel = new MahasiswaModel();
        // $nimBaru = $this->generateNIM();

        $foto = $this->request->getFile('foto');
        if ($foto->getError() == 4) {
            $nama_foto = '';
        } else {
            $nama_foto = $foto->getRandomName();
            $foto->move('profile', $nama_foto);
        }

        $mahasiswaModel->insert([
            'nim' => $this->request->getPost('nim'),
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'alamat' => $this->request->getPost('alamat'),
            'no_handphone' => $this->request->getPost('no_handphone'),
            'semester' => $this->request->getPost('semester'),
            'jurusan' => $this->request->getPost('jurusan'),
            'foto' => $nama_foto,
            'nama_ortu' => $this->request->getPost('nama_ortu'),
            'jk_ortu' => $this->request->getPost('jenis_kelamin_ortu'),
            'nohp_ortu' => $this->request->getPost('no_whatsapp'),
        ]);

        $id_mahasiswa = $mahasiswaModel->insertID();
        $userModel = new UserModel();
        $userModel->insert([
            'id_mahasiswa' => $id_mahasiswa,
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status' => 'aktif',
            'role' => 'mahasiswa'
        ]);

        return redirect()->to('/admin/data_mahasiswa')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $lokasi_presensi = new LokasiPresensiMOdel();
        $mahasiswaModel = new MahasiswaModel();
        $data = [
            'title' => 'Edit mahasiswa',
            'mahasiswa' => $mahasiswaModel->editmahasiswa($id),
            'lokasi_presensi' => $lokasi_presensi->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/data_mahasiswa/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'nim' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "NIM Wajib Diisi"
                ],
            ],
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
            'semester' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Semester Wajib Diisi"
                ],
            ],
            'jurusan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jurusan Wajib Diisi"
                ],
            ],
            'username' => [
                'rules' => 'required|is_unique[users.username,id_mahasiswa,' . $id . ']',
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
            'nama_ortu' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Orang Tua Wajib Diisi"
                ],
            ],
            'jenis_kelamin_ortu' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jenis Kelamin Orang Tua Wajib Diisi"
                ],
            ],
            'no_whatsapp' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "No WhatsApp Orang Tua Wajib Diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $lokasi_presensi = new LokasiPresensiModel();
            $mahasiswaModel = new MahasiswaModel();

            $data = [
                'title' => 'Edit mahasiswa',
                'mahasiswa' => $mahasiswaModel->editmahasiswa($id),
                'lokasi_presensi' => $lokasi_presensi->findAll(),
                'validation' => \Config\Services::validation()
            ];

            return view('admin/data_mahasiswa/edit', $data);
        } else {
            $mahasiswaModel = new MahasiswaModel();
            $userModel = new UserModel();

            // Ambil data mahasiswa lama
            $mahasiswaLama = $mahasiswaModel->find($id);

            if (!$mahasiswaLama) {
                session()->setFlashData('error', 'Data mahasiswa tidak ditemukan');
                return redirect()->to(base_url('admin/data_mahasiswa'));
            }

            // Proses upload foto baru jika ada
            $foto = $this->request->getFile('foto');
            if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                $nama_foto = $foto->getRandomName();
                $foto->move('profile', $nama_foto);

                // Hapus foto lama jika ada
                if (!empty($mahasiswaLama['foto']) && file_exists('profile/' . $mahasiswaLama['foto'])) {
                    unlink('profile/' . $mahasiswaLama['foto']);
                }
            } else {
                $nama_foto = $this->request->getPost('foto_lama'); // Pakai foto lama jika tidak ada upload baru
            }

            // Update data mahasiswa
            $mahasiswaModel->update($id, [
                'nama' => $this->request->getPost('nama'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'alamat' => $this->request->getPost('alamat'),
                'no_handphone' => $this->request->getPost('no_handphone'),
                'semester' => $this->request->getPost('semester'),
                'jurusan' => $this->request->getPost('jurusan'),
                'foto' => $nama_foto,
                'nama_ortu' => $this->request->getPost('nama_ortu'),
                'jk_ortu' => $this->request->getPost('jenis_kelamin_ortu'),
                'nohp_ortu' => $this->request->getPost('no_whatsapp'),
            ]);

            // Cek apakah password diubah
            if ($this->request->getPost('password') == '') {
                $password = $this->request->getPost('password_lama');
            } else {
                $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }

            // Update data user
            $userModel
                ->where('id_mahasiswa', $id)
                ->set([
                    'username' => $this->request->getPost('username'),
                    'password' => $password,
                    'status' => $this->request->getPost('status'),
                    'role' => 'mahasiswa'
                ])
                ->update();

            session()->setFlashData('success', 'Data mahasiswa Berhasil Diupdate');
            return redirect()->to(base_url('admin/data_mahasiswa'));
        }
    }

    public function delete($id)
    {
        $mahasiswaModel = new MahasiswaModel();
        $userModel = new UserModel(); // Tambahkan ini jika belum dideklarasikan
        $mahasiswa = $mahasiswaModel->find($id); // Perbaikan pemanggilan variabel

        if ($mahasiswa) {
            $userModel->where('id_mahasiswa', $id)->delete(); // Hapus data user terkait
            $mahasiswaModel->delete($id); // Hapus data mahasiswa

            return redirect()->to('/admin/data_mahasiswa')->with('success', 'Data Berhasil dihapus.');
        }

        return redirect()->to('/admin/data_mahasiswa')->with('error', 'Data mahasiswa tidak ditemukan.');
    }

    public function import()
    {
        $rules = [
            'file' => [
                'rules' => 'uploaded[file]|mime_in[file,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet]|ext_in[file,xls,xlsx]',
                'errors' => [
                    'uploaded' => 'File tidak boleh kosong!',
                    'mime_in' => 'Format tidak didukung.',
                    'ext_in' => 'File harus xls atau xlsx.'
                ]
            ]
        ];

        if (!$this->validate($rules)) return redirect()->back()->with('error', array_values($this->validator->getErrors())[0]);

        $file = $this->request->getFile('file');

        $import = new ImportController();
        $return = $import->saveToModel($file, ['npm', 'nama']);

        if (!$return['success']) {
            return redirect()->back()->with('error', $return['message']);
        }

        $success = $return['totalOfSuccessData'] . ' data berhasil disimpan';
        $failed = $return['totalOfFailedData'] > 0 ? ' dan ' . $return['totalOfFailedData'] . ' gagal tersimpan.' : '.';

        return redirect()->back()->with('success', $success . $failed);
    }
}
