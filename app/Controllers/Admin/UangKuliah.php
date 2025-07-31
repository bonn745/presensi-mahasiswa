<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Controllers\ImportController;
use App\Models\JadwalPembayaran;
use App\Models\MahasiswaModel;
use App\Models\UangKuliah as ModelsUangKuliah;

class UangKuliah extends BaseController
{
    function __construct()
    {
        helper(['url', 'form']);
    }

    public function data()
    {
        $uangKuliahModel = new ModelsUangKuliah();
        $uangKuliah = $uangKuliahModel->select('jenis_pembayaran, mahasiswa.nama, mahasiswa.npm')
            ->join('mahasiswa', 'mahasiswa.npm = uang_kuliah.npm')
            ->findAll();

        $data = [
            'title' => 'Data Pembayaran',
            'uang_kuliah' => $uangKuliah,
        ];

        return view('admin/uang_kuliah/data_pembayaran', $data);
    }

    public function jadwal()
    {
        $jadwalPembayaranModel = new JadwalPembayaran();
        $jadwalPembayaran = $jadwalPembayaranModel->orderBy('status_jadwal','ASC')->findAll();
        $data = [
            'title' => 'Jadwal Pembayaran',
            'jadwal' => $jadwalPembayaran,
        ];

        return view('admin/uang_kuliah/jadwal_pembayaran', $data);
    }

    public function create()
    {
        $mahasiswaModel = new MahasiswaModel();
        $data = [
            'title' => 'Tambah Data Pembayaran',
            'mahasiswa' => $mahasiswaModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/uang_kuliah/create', $data);
    }

    public function createJadwal()
    {
        $data = [
            'title' => 'Buat Jadwal Pembayaran Baru',
        ];

        return view('admin/uang_kuliah/create_jadwal', $data);
    }

    public function store()
    {
        $rules = [
            'mahasiswa' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Mahasiswa Wajib Diisi"
                ],
            ],
            'jenis_pembayaran' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jenis Pembayaran Wajib Diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->with('data', $this->request->getVar());
        }

        $uangKuliahModel = new ModelsUangKuliah();

        $data = array(
            'npm' => $this->request->getPost('mahasiswa'),
            'jenis_pembayaran' => $this->request->getPost('jenis_pembayaran'),
        );

        $uangKuliahModel->insert($data);

        return redirect()->route('admin.uangkuliah.data')->with('message', 'Data pembayaran berhasil ditambahkan.');
    }

    public function storeJadwal()
    {
        $rules = [
            'tanggal_pembayaran_tahap_1' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal Pembayaran Tahap 1 Wajib Diisi"
                ],
            ],
            'tanggal_pembayaran_tahap_2' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal Pembayaran Tahap 2 Wajib Diisi"
                ],
            ],
            'tanggal_pembayaran_tahap_3' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal Pembayaran Tahap 3 Wajib Diisi"
                ],
            ],
            'tanggal_notifikasi_tahap_1' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal Notifikasi Tahap 1 Wajib Diisi"
                ],
            ],
            'tanggal_notifikasi_tahap_2' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal Notifikasi Tahap 2 Wajib Diisi"
                ],
            ],
            'tanggal_notifikasi_tahap_3' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal Notifikasi Tahap 3 Wajib Diisi"
                ],
            ],
            'jam_notifikasi_tahap_1' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jam Notifikasi Tahap 1 Wajib Diisi"
                ],
            ],
            'jam_notifikasi_tahap_2' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jam Notifikasi Tahap 2 Wajib Diisi"
                ],
            ],
            'jam_notifikasi_tahap_3' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Jam Notifikasi Tahap 3 Wajib Diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->with('data', $this->request->getVar());
        }

        $jadwalPembayaranModel = new JadwalPembayaran();
        $jadwalPembayaranLamaModel = new JadwalPembayaran();
        $jadwalPembayaranLamaModel->where('status_jadwal', 'Aktif');
        $jadwalPembayaranLamaModel->update(null, [
            'status_jadwal' => 'Nonaktif'
        ]);
        $jadwalPembayaranModel->insert([
            'tanggal_pembayaran_tahap_1' => $this->request->getPost('tanggal_pembayaran_tahap_1'),
            'tanggal_pembayaran_tahap_2' => $this->request->getPost('tanggal_pembayaran_tahap_2'),
            'tanggal_pembayaran_tahap_3' => $this->request->getPost('tanggal_pembayaran_tahap_3'),
            'tanggal_notifikasi_tahap_1' => $this->request->getPost('tanggal_notifikasi_tahap_1'),
            'tanggal_notifikasi_tahap_2' => $this->request->getPost('tanggal_notifikasi_tahap_2'),
            'tanggal_notifikasi_tahap_3' => $this->request->getPost('tanggal_notifikasi_tahap_3'),
            'jam_notifikasi_tahap_1' => $this->request->getPost('jam_notifikasi_tahap_1'),
            'jam_notifikasi_tahap_2' => $this->request->getPost('jam_notifikasi_tahap_2'),
            'jam_notifikasi_tahap_3' => $this->request->getPost('jam_notifikasi_tahap_3'),
        ]);

        return redirect()->route('admin.uangkuliah.jadwal')->with('message', 'Jadwal berhasil ditambahkan.');
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
        $return = $import->saveDataPembayaran($file, ['npm', 'jenis_pembayaran']);

        if (!$return['success']) {
            return redirect()->back()->with('error', $return['message']);
        }

        $success = $return['totalOfSuccessData'] . ' data berhasil disimpan';
        $failed = $return['totalOfFailedData'] > 0 ? ' dan ' . $return['totalOfFailedData'] . ' gagal tersimpan.' : '.';

        return redirect()->back()->with('success', $success . $failed);
    }
}
