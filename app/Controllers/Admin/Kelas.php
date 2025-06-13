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
            'kelas' => $this->kelasModel->findAll()
        ];
        return view('admin/kelas/kelas', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Kelas',
            'matkul' => $this->matkulModel->findAll()
        ];
        return view('admin/kelas/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        if (!$this->validate([
            'ruangan'    => 'required|max_length[100]',
            'hari'       => 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu]',
            'jam_masuk'  => 'required',
            'jam_pulang' => 'required',
            'matkul'     => 'required|max_length[255]',
        ])) {
            return redirect()->to('/admin/kelas/create')->withInput()->with('errors', $validation->getErrors());
        }

        $matkulNama = $this->request->getPost('matkul');
        $matkulData = $this->matkulModel->where('matkul', $matkulNama)->first();

        if (!$matkulData) {
            return redirect()->to('/admin/kelas/create')->withInput()->with('errors', ['matkul' => 'Mata kuliah tidak ditemukan di database.']);
        }


        $data = [
            'ruangan'    => $this->request->getPost('ruangan'),
            'hari'       => $this->request->getPost('hari'),
            'jam_masuk'  => $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang'),
            'matkul'     => $matkulNama,
            'id_matkul'  => $matkulData['id']

        ];

        $this->kelasModel->insert($data);

        session()->setFlashdata('success_add', 'Jadwal kelas berhasil ditambahkan.');
        return redirect()->to('/admin/kelas');
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
        ])) {
            return redirect()->to('/admin/kelas/edit/' . $id)
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        // Ambil data dari form
        $matkulNama = $this->request->getPost('matkul');
        $matkulData = $this->matkulModel->where('matkul', $matkulNama)->first();

        if (!$matkulData) {
            return redirect()->to('/admin/kelas/edit/' . $id)
                ->withInput()
                ->with('errors', ['matkul' => 'Mata kuliah tidak ditemukan di database.']);
        }

        // Siapkan data untuk update
        $updateData = [
            'ruangan'    => $this->request->getPost('ruangan'),
            'hari'       => $this->request->getPost('hari'),
            'jam_masuk'  => $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang'),
            'matkul'     => $matkulNama,
            'id_matkul'  => $matkulData['id']
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
