<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    public function login()
    {
        $data = [
            'validation' => \Config\Services::validation()
        ];
        return view('login', $data);
    }

    public function login_action()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            $data['validation'] = $this->validator;
            return view('login', $data);
        } else {
            $session = session();
            $loginModels = new LoginModel;

            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');
            $cekusername = $loginModels->where('username', $username)->first();

            if ($cekusername) {
                $password_db = $cekusername['password'];
                $cek_password = password_verify($password, $password_db);

                if ($cek_password) {
                    $lengkapiData = false;
                    if ($cekusername['role'] == 'mahasiswa') {
                        $mahasiswaModel = new MahasiswaModel();
                        $dataMahasiswa = $mahasiswaModel->find($cekusername['id_mahasiswa']);
                        if (!is_null($dataMahasiswa)) {
                            if (
                                is_null($dataMahasiswa['nama_ortu']) ||
                                empty($dataMahasiswa['nama_ortu']) ||
                                is_null($dataMahasiswa['jk_ortu']) ||
                                empty($dataMahasiswa['jk_ortu']) ||
                                is_null($dataMahasiswa['nohp_ortu']) ||
                                empty($dataMahasiswa['nohp_ortu'])
                            ) {
                                $lengkapiData = true;
                            }
                        }
                    }
                    $session_data = [
                        'username' => $cekusername['username'],
                        'nama_mahasiswa' => $dataMahasiswa['nama'],
                        'logged_in' => true,
                        'role_id' => $cekusername['role'],
                        'id_mahasiswa' => $cekusername['id_mahasiswa'],
                        'id_dosen' => $cekusername['id_dosen'],
                        'lengkapi_data' => $lengkapiData,
                    ];
                    $session->set($session_data);

                    switch ($cekusername['role']) {
                        case "admin":
                            return redirect()->to('admin/home');
                        case "mahasiswa":
                            return redirect()->to('mahasiswa/home');
                        case "dosen":
                            return redirect()->to('dosen/home');
                        default:
                            $session->setFlashdata('pesan', 'Akun anda belum terdaftar.');
                            return redirect()->to('/');
                    }
                } else {
                    $session->setFlashdata('pesan', 'Password salah, silakan coba lagi.');
                    return redirect()->to('/');
                }
            } else {
                $session->setFlashdata('pesan', 'Username tidak ditemukan.');
                return redirect()->to('/');
            }
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}
