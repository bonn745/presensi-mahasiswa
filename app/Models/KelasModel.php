<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table      = 'kelas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'ruangan',
        'hari',
        'jam_masuk',
        'jam_pulang',
        'jenis_kelas',
        'id_matkul',
    ];

    // protected $validationRules = [
    //     'ruangan'    => 'required',
    //     'hari'       => 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu]',
    //     'jam_masuk'  => 'required',
    //     'jam_pulang' => 'required',
    //     'matkul'     => 'required',
    // ];

    // protected $validationMessages = [
    //     'ruangan' => [
    //         'required' => 'Ruangan harus diisi.',
    //         'max_length' => 'Ruangan maksimal 100 karakter.',
    //     ],
    //     'hari' => [
    //         'required' => 'Hari harus dipilih.',
    //         'in_list' => 'Pilih hari yang valid.',
    //     ],
    //     'jam_masuk' => [
    //         'required' => 'Jam masuk harus diisi.',
    //         'regex_match' => 'Format jam masuk harus HH:MM.',
    //     ],
    //     'jam_pulang' => [
    //         'required' => 'Jam pulang harus diisi.',
    //         'regex_match' => 'Format jam pulang harus HH:MM.',
    //     ],
    //     'matkul' => [
    //         'required' => 'Mata kuliah harus dipilih.',
    //     ],
    // ];

    // protected $skipValidation = false;

    public function getKelasByMatkul($matkul)
    {
        return $this->where('matkul', $matkul)->findAll();
    }

    public function addJadwalKelas($data)
    {
        // Cari id_matkul berdasarkan nama matkul (yang dikirim dari form)
        $matkulModel = new \App\Models\MatkulModel();
        $matkulData = $matkulModel->where('matkul', $data['matkul'])->first();

        // Jika tidak ditemukan, return false
        if (!$matkulData) {
            return false;
        }

        // Tambahkan id_matkul dan id_dosen ke data
        $data['id_matkul'] = $matkulData['id'];

        // Simpan ke database
        return $this->insert($data);
    }

    public function updateJadwalKelas($id, $data)
    {
        return $this->builder()
            ->where('id', $id)
            ->update($data);
    }

    public function deleteJadwalKelas($id)
    {
        return $this->delete($id);
    }
}
