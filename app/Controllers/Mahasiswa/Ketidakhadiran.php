<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\ketidakhadiranModel;
use App\Models\MatkulMahasiswa;
use Carbon\Carbon;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Database\BaseBuilder;
use DateInterval;
use DatePeriod;
use DateTime;

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
            'ketidakhadiran' => $ketidakhadiranModel->join('matkul', 'matkul.id = ketidakhadiran.id_matkul')->where('id_mahasiswa', $id_mahasiswa)->findAll()
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
        if (!($this->validate($rules))) {
            return redirect()->back()->with('error', array_values($this->validator->getErrors())[0]);
        }

        // return json_encode($this->request->getVar());

        // if (!$this->validate($rules)) {
        //     $data = [
        //         'title' => 'Ajukan Permohonan',
        //         'validation' => $this->validation
        //     ];
        //     return view('mahasiswa/create_ketidakhadiran', $data);
        // }

        $jumlahHari = 0;
        $namaHari = null;
        $dataTanggal = null;

        if (empty($this->request->getPost('tanggal_selesai')) || $this->request->getPost('tanggal_mulai') == $this->request->getPost('tanggal_selesai')) {
            $jumlahHari = 1;
            $dataTanggal[] = $this->request->getPost('tanggal_mulai');
            $namaHari[] = Carbon::createFromFormat('Y-m-d', $this->request->getPost('tanggal_mulai'))->locale('id')->translatedFormat('l');
        } else {
            if ($this->request->getPost('tanggal_mulai') > $this->request->getPost('tanggal_selesai')) {
                return redirect()->back()->with('error', 'Tanggal selesai harus sama atau lebih besar dari tanggal mulai.');
            }

            $tanggal_mulai = date('Y-m-d', strtotime($this->request->getPost('tanggal_mulai')));
            $tanggal_selesai = date('Y-m-d', strtotime($this->request->getPost('tanggal_selesai') . '+1 day'));

            $period = new DatePeriod(
                new DateTime($tanggal_mulai),
                new DateInterval('P1D'),
                new DateTime($tanggal_selesai)
            );

            foreach ($period as $tanggal) {
                $jumlahHari++;
                $dataTanggal[] = $tanggal->format('Y-m-d');
                $namaHari[] = Carbon::createFromFormat('Y-m-d', $tanggal->format('Y-m-d'))->locale('id')->translatedFormat('l');
            }
        }

        if ($jumlahHari == 1) {
            if (empty($this->request->getPost('matkul'))) {
                return redirect()->back()->with('error', 'Mata kuliah harus dipilih.');
            }

            if (!empty($dataTanggal)) {
                $file = $this->request->getFile('file');
                if ($file->getError() == 4) {
                    $nama_file = '';
                } else {
                    $nama_file = $file->getRandomName();
                    $file->move('file_ketidakhadiran', $nama_file);
                }

                foreach ($this->request->getPost('matkul') as $matkul) {
                    $ketidakhadiranModel = new KetidakhadiranModel();
                    $ketidakhadiranModel->insert([
                        'keterangan' => $this->request->getPost('keterangan'),
                        'tanggal' => $this->request->getPost('tanggal_mulai'),
                        'id_mahasiswa' => $this->request->getPost('id_mahasiswa'),
                        'deskripsi' => $this->request->getPost('deskripsi'),
                        'status_pengajuan' => 'Pending',
                        'id_matkul' => $matkul,
                        'file' => $nama_file,
                    ]);
                }

                return redirect()->route('mahasiswa.ketidakhadiran.index')->with('success', 'Permohonan berhasil diajukan.');
            }
        }

        if (!empty($dataTanggal)) {
            $file = $this->request->getFile('file');
            if ($file->getError() == 4) {
                $nama_file = '';
            } else {
                $nama_file = $file->getRandomName();
                $file->move('file_ketidakhadiran', $nama_file);
            }
            for ($i = 0; $i < count($dataTanggal); $i++) {
                $hari = $namaHari[$i];
                $tanggal = $dataTanggal[$i];
                $matkulMhsModel = new MatkulMahasiswa();
                $kelasModel = new KelasModel();
                $matkulMhs = $matkulMhsModel->select('matkul_id')->where('mhs_id', session('id_mahasiswa'))->findAll();
                $matkulIds = [];

                foreach ($matkulMhs as $matkul) {
                    $matkulIds[] = $matkul['matkul_id'];
                }

                $matkulIds = $kelasModel->select('id_matkul')->where('hari', $hari)->whereIn('id_matkul', $matkulIds)->findAll();

                foreach ($matkulIds as $matkul) {
                    $ketidakhadiranModel = new KetidakhadiranModel();
                    $ketidakhadiranModel->insert([
                        'keterangan' => $this->request->getPost('keterangan'),
                        'tanggal' => $tanggal,
                        'id_mahasiswa' => $this->request->getPost('id_mahasiswa'),
                        'deskripsi' => $this->request->getPost('deskripsi'),
                        'status_pengajuan' => 'Pending',
                        'id_matkul' => $matkul['id_matkul'],
                        'file' => $nama_file,
                    ]);
                }
            }

            return redirect()->route('mahasiswa.ketidakhadiran.index')->with('success', 'Permohonan berhasil diajukan.');
        }

        return redirect()->route('mahasiswa.ketidakhadiran.index')->with('error', 'Terjadi kesalahan.');
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

    public function getMatkul()
    {
        $rules = [
            'tgl_mulai' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Tanggal mulai harus diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'message' => array_values($this->validator->getErrors())[0]
            ])->setStatusCode(400);
        }

        $matkulMhsModel = new MatkulMahasiswa();
        $kelasModel = new KelasModel();
        $mhsId = session()->get('id_mahasiswa');
        $matkulMhs = $matkulMhsModel->select('matkul_id')->where('mhs_id', $mhsId)->findAll();
        $matkulIds = [];
        foreach ($matkulMhs as $matkul) {
            $matkulIds[] = $matkul['matkul_id'];
        }

        if (empty($this->request->getPost('tgl_selesai'))) {
            $hari = Carbon::createFromFormat('Y-m-d', $this->request->getPost('tgl_mulai'))->locale('id')->translatedFormat('l');

            $data = $kelasModel->select('id_matkul as id, matkul.matkul as mata_kuliah')->join('matkul', 'matkul.id = kelas.id_matkul')->whereIn('id_matkul', $matkulIds)->where('hari', $hari)->findAll();

            if (empty($data)) return response()->setJSON([
                'message' => 'Data tidak ditemukan',
            ])->setStatusCode(404);

            return response()->setJSON([
                'data' => $data
            ])->setStatusCode(200);
        } else {
            $tanggal_mulai = $this->request->getPost('tgl_mulai');
            $tanggal_selesai = $this->request->getPost('tgl_selesai');

            if ($tanggal_mulai != $tanggal_selesai) {
                if ($tanggal_selesai < $tanggal_mulai) return response()->setJSON([
                    'message' => 'Tanggal selesai harus lebih besar dari tanggal mulai.'
                ])->setStatusCode(400);

                return response()->setJSON([
                    'message' => 'Tidak ada data.'
                ])->setStatusCode(400);
            } else {
                $hari = Carbon::createFromFormat('Y-m-d', $this->request->getPost('tgl_mulai'))->locale('id')->translatedFormat('l');

                $data = $kelasModel->select('id_matkul as id, matkul.matkul as mata_kuliah')->join('matkul', 'matkul.id = kelas.id_matkul')->whereIn('id_matkul', $matkulIds)->where('hari', $hari)->findAll();

                if (empty($data)) return response()->setJSON([
                    'message' => 'Data tidak ditemukan',
                ])->setStatusCode(404);

                return response()->setJSON([
                    'data' => $data
                ])->setStatusCode(200);
            }
        }
    }
}
