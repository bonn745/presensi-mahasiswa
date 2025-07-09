<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\MatkulMahasiswa;
use App\Models\MatkulModel;
use CodeIgniter\HTTP\ResponseInterface;

class MataKuliahController extends BaseController
{
    public function index()
    {
        $id_mhs = session()->get('id_mahasiswa');
        $matkulMahasiswa = new MatkulMahasiswa();
        $matkul = $matkulMahasiswa->select('
                matkul.matkul, 
                kelas.jenis_kelas, 
                dosen.nama_dosen, 
                lokasi_presensi.nama_ruangan, 
                lokasi_presensi.alamat_lokasi, 
                lokasi_presensi.tipe_lokasi,
                kelas.jam_masuk,
                kelas.jam_pulang,
                kelas.hari
            ')
            ->join('matkul', 'matkul.id = matkul_mahasiswa.matkul_id', 'left')
            ->join('kelas', 'kelas.id_matkul = matkul.id', 'left')
            ->join('dosen', 'dosen.id = matkul.dosen_pengampu', 'left')
            ->join('lokasi_presensi', 'lokasi_presensi.id = kelas.ruangan', 'left')
            ->where('mhs_id', $id_mhs)
            ->findAll();

        $data = [
            'title' => 'Mata Kuliah',
            'mata_kuliah' => $matkul,
        ];

        return view('Mahasiswa/mata_kuliah', $data);
    }

    public function create()
    {
        $matkulModel = new MatkulModel();
        $mahasiswaModel = new MahasiswaModel();
        $matkulMahasiswa = new MatkulMahasiswa();
        $id_mhs = session()->get('id_mahasiswa');
        $mhs = $mahasiswaModel->find($id_mhs);
        $matkul = $matkulMahasiswa->where('mhs_id',$id_mhs)->findAll();
        $ids = array_column(array_values($matkul),'matkul_id');

        $data = [
            'title' => 'Pilih Mata Kuliah',
            'matkul' => $matkulModel->where('prodi_id', $mhs['prodi'])->whereNotIn('id',$ids)->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('Mahasiswa/pilih_matkul', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'matkul.*'    => 'required|min_length[1]',
            'id_mahasiswa' => 'required',
        ])) {
            return redirect()->back()->with('error', array_values($this->validator->getErrors())[0]);
        }

        $data = [];

        foreach ($this->request->getPost('matkul') as $matkul) {
            $data[] = array(
                'matkul_id' => $matkul,
                'mhs_id' => $this->request->getPost('id_mahasiswa')
            );
        }

        $matkulMahasiswa = new MatkulMahasiswa();

        $matkulMahasiswa->insertBatch($data);

        return redirect()->route('mahasiswa.home')->with('message', 'Data mata kuliah berhasil ditambahkan.');
    }
}
