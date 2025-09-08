<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'id_mahasiswa' => null,
            'id_dosen'     => null,
            'username'     => 'admin',
            'password'     => password_hash('admin12345', PASSWORD_DEFAULT),
            'status'       => 'aktif',
            'role'         => 'admin',
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        $this->db->table('users')->insert($data);
    }
}
