<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Mahasiswa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'npm' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'jenis_kelamin' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true
            ],
            'alamat' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'no_handphone' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ],
            'semester' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ],
            'prodi' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true
            ],
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'nama_ortu' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true
            ],
            'jk_ortu' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
                'null' => true
            ],
            'nohp_ortu' => [
                'type' => 'VARCHAR',
                'constraint' => '13',
                'null' => true
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('mahasiswa');
    }

    public function down()
    {
        $this->forge->dropTable('mahasiswa');
    }
}
