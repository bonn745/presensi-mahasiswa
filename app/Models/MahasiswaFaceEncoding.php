<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaFaceEncoding extends Model
{
    protected $table            = 'mahasiswa_face_encodings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_mahasiswa',
        'face_encoding',
        'foto_path',
        'is_active',
    ];
}
