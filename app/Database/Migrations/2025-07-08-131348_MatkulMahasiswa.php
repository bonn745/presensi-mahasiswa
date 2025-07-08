<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MatkulMahasiswa extends Migration
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
            'matkul_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'mhs_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('matkul_mahasiswa');
    }

    public function down()
    {
        $this->forge->dropTable('matkul_mahasiswa');

    }
}
