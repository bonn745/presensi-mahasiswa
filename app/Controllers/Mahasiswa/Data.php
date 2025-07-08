<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use CodeIgniter\HTTP\ResponseInterface;

class Data extends BaseController
{
    function __construct()
    {
        helper(['url', 'form']);
    }

    public function index($id)
    {
        $mahasiswaModel = new MahasiswaModel();
        $mahasiswa = $mahasiswaModel->find($id);
        $data = [
            'title' => $mahasiswa['nama'],
            'mahasiswa' => $mahasiswa,
            'validation' => \Config\Services::validation(),
        ];

        return view('Mahasiswa/lengkapi', $data);
    }

    public function store()
    {
        $rules = [
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
            return redirect()->back()->with('errors', $this->validator->getErrors())->with('data', $this->request->getPost());
        }

        $mahasiswaModel = new MahasiswaModel();

        $foto = $this->request->getFile('foto');
        if ($foto->getError() == 4) {
            $nama_foto = '';
        } else {
            $nama_foto = $foto->getRandomName();
            $foto->move('profile', $nama_foto);
        }

        $mahasiswaModel->update($this->request->getPost('id'), [
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

        $session_data = [
            'lengkapi_data' => false,
        ];
        
        $session = session();
        $session->set($session_data);

        return redirect()->route('mahasiswa.home');
    }
}
