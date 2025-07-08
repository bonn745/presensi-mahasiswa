<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\MatkulModel;
use App\Models\DosenModel;

class Kelas extends BaseController
{
    protected $kelasModel;
    protected $matkulModel;
    protected $dosenModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->matkulModel = new MatkulModel();
        $this->dosenModel = new DosenModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Kelas',
            'kelas' => $this->kelasModel
                ->select('kelas.*, dosen.nama_dosen, matkul.matkul, prodi.nama as nama_prodi')
                ->join('matkul', 'matkul.id = kelas.id_matkul')
                ->join('dosen', 'dosen.id = matkul.dosen_pengampu')
                ->join('prodi', 'prodi.id = matkul.prodi_id')
                ->findAll()
        ];
        return view('admin/kelas/kelas', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Kelas',
            'matkul' => $this->matkulModel->select('matkul.id, matkul.matkul')->findAll() // Mengambil semua data Matkul
        ];
        return view('admin/kelas/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'ruangan'    => 'required|numeric',
            'hari'       => 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu]',
            'jam_masuk'  => 'required',
            'jam_pulang' => 'required',
            'matkul'     => 'required',
            'jenis_kelas'     => 'required|in_list[Daring,Luring]',
        ])) {
            return redirect()->back()->withInput()->with('error', array_values($this->validator->getErrors())[0]);
        }

        $data = [
            'ruangan'    => $this->request->getPost('ruangan'),
            'hari'       => $this->request->getPost('hari'),
            'jam_masuk'  => $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang'),
            'id_matkul'  => $this->request->getPost('matkul'),
            'jenis_kelas'  => $this->request->getPost('jenis_kelas'),

        ];

        $this->kelasModel->insert($data);

        return redirect()->to('/admin/kelas')->with('success_add', 'Jadwal kelas berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $data = [
            'kelas'  => $this->kelasModel->find($id),
            'matkul' => $this->matkulModel->findAll(),
            'title'  => 'Edit Data Kelas',
        ];

        if (!$data['kelas']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Jadwal kelas tidak ditemukan');
        }

        return view('admin/kelas/edit', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();

        // Validasi input
        if (!$this->validate([
            'ruangan'    => 'required',
            'hari'       => 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu]',
            'jam_masuk'  => 'required',
            'jam_pulang' => 'required',
            'matkul'     => 'required',
            'jenis_kelas'     => 'required',
        ])) {
            return redirect()->to('/admin/kelas/edit/' . $id)
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        // Siapkan data untuk update
        $updateData = [
            'ruangan'    => $this->request->getPost('ruangan'),
            'hari'       => $this->request->getPost('hari'),
            'jam_masuk'  => $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang'),
            'id_matkul'     => $this->request->getPost('matkul'),
            'jenis_kelas'  => $this->request->getPost('jenis_kelas'),
        ];

        // Lakukan update menggunakan builder
        $builder = $this->kelasModel->builder();
        $updated = $builder->where('id', $id)
            ->update($updateData);

        if ($updated) {
            session()->setFlashdata('success', 'Jadwal kelas berhasil diperbarui.');
            return redirect()->to('/admin/kelas');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui jadwal kelas.');
            return redirect()->to('/admin/kelas/edit/' . $id)->withInput();
        }
    }
    public function delete($id)
    {
        $this->kelasModel->delete($id);

        session()->setFlashdata('success_delete', 'Data berhasil dihapus.');
        return redirect()->to('/admin/kelas');
    }
}
